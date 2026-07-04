<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    /**
     * Display a listing of approved books.
     *
     * Supports:
     * - Filtering: ?filter[category_id]=1&filter[title]=flutter&filter[author]=john
     * - Sorting: ?sort=-created_at,title (descending created_at, ascending title)
     * - Includes: ?include=category,creator,reviews
     * - Pagination: ?per_page=15&page=1
     */
    public function index(Request $request): JsonResponse
    {
        $books = QueryBuilder::for(Book::class)
            ->approved()
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::partial('title'),
                AllowedFilter::partial('author'),
                AllowedFilter::exact('file_type'),
            ])
            ->allowedSorts([
                AllowedSort::field('title'),
                AllowedSort::field('created_at'),
                AllowedSort::field('release_date'),
                AllowedSort::field('average_rating'),
                AllowedSort::field('number_of_pages'),
            ])
            ->defaultSort('-created_at')
            ->allowedIncludes([
                AllowedInclude::relationship('category'),
                AllowedInclude::relationship('creator'),
                AllowedInclude::relationship('reviews'),
                AllowedInclude::relationship('reviews.user'),
                AllowedInclude::count('reviews'),
            ])
            ->with('category')
            ->paginate($request->get('per_page', 15))
            ->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $books,
        ]);
    }

    /**
     * Display a specific book.
     */
    public function show(Request $request, Book $book): JsonResponse
    {
        // Only show approved books to public, unless owner or admin
        if (! $book->isApproved()) {
            /** @var \App\Models\User|null $user */
            $user = auth('sanctum')->user();

            if (! $user || ($book->created_by !== $user->id && $user->role !== 'admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book not found.',
                ], 404);
            }
        }

        $book->load([
            'category',
            'creator:id,name,email',
            'reviews' => fn ($q) => $q->with('user:id,name')->latest()->limit(5),
        ]);

        $book->loadCount('reviews');

        return response()->json([
            'success' => true,
            'data' => $book,
        ]);
    }

    /**
     * Stream a book's PDF file to the reader.
     *
     * Resolution order (local is always preferred over remote):
     *   1. An uploaded MediaLibrary `book_file` -> served straight from disk.
     *   2. A relative `file_url` on the public disk -> served straight from disk.
     *   3. A genuinely external http(s) `file_url` -> proxied to dodge CORS,
     *      with redirect-following, retries and graceful JSON errors.
     *
     * Serving local files directly is not just faster: the production server
     * runs a single-process `php -S`, so proxying a URL that points back at
     * this same app would deadlock the worker.
     */
    public function stream(Book $book)
    {
        if (! $book->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found.',
            ], 404);
        }

        // 1) Uploaded MediaLibrary file -> stream from local disk.
        $media = $book->getFirstMedia('book_file');
        if ($media && is_file($media->getPath())) {
            return $this->streamLocalFile($media->getPath(), $book->title, $media->mime_type);
        }

        // 2) Relative file_url stored on the public disk -> stream from local disk.
        $fileUrl = $book->file_url;
        if ($fileUrl && ! Str::startsWith($fileUrl, ['http://', 'https://'])) {
            $localPath = Storage::disk('public')->path($fileUrl);
            if (is_file($localPath)) {
                return $this->streamLocalFile($localPath, $book->title);
            }
        }

        // 3) External URL -> proxy it.
        if ($fileUrl && Str::startsWith($fileUrl, ['http://', 'https://'])) {
            return $this->proxyExternalFile($book, $fileUrl);
        }

        return response()->json([
            'success' => false,
            'message' => 'Book file is not available.',
        ], 404);
    }

    /**
     * Stream a file that lives on the local filesystem.
     *
     * BinaryFileResponse handles Content-Length and HTTP range requests for us.
     */
    private function streamLocalFile(string $path, string $title, ?string $mimeType = null)
    {
        return response()->file($path, [
            'Content-Type' => $mimeType ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->safeFilename($title).'.pdf"',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Proxy an external PDF, streaming it through in chunks.
     *
     * SSL verification stays ON. On a cert/host/network failure or an upstream
     * error we return a clean JSON error rather than a bare abort(502), and we
     * retry transient failures (connection resets, 5xx) a couple of times since
     * some free hosts (e.g. archive.org nodes) intermittently return 503.
     */
    private function proxyExternalFile(Book $book, string $fileUrl)
    {
        $client = new \GuzzleHttp\Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => true, // Keep SSL certificate verification enabled.
            'allow_redirects' => [
                'max' => 5,
                'strict' => false,
                'referer' => true,
                'protocols' => ['http', 'https'],
                'track_redirects' => false,
            ],
            'http_errors' => true,
        ]);

        $requestOptions = [
            'stream' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'application/pdf,application/octet-stream,*/*',
            ],
        ];

        $maxAttempts = 3;
        $upstream = null;
        $lastError = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $upstream = $client->request('GET', $fileUrl, $requestOptions);
                break;
            } catch (ClientException $e) {
                // 4xx: the file is genuinely missing/forbidden. Do not retry.
                Log::warning('Book file unavailable upstream (4xx)', [
                    'book_id' => $book->id,
                    'url' => $fileUrl,
                    'status' => $e->getResponse()?->getStatusCode(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'The book file could not be found at its source.',
                ], 404);
            } catch (ConnectException|ServerException $e) {
                // Network/SSL failure or 5xx: transient, worth another try.
                $lastError = $e;
                if ($attempt < $maxAttempts) {
                    usleep(300000 * $attempt); // 0.3s, 0.6s backoff
                }
            } catch (GuzzleException $e) {
                // Too many redirects, etc. Not retryable.
                $lastError = $e;
                break;
            }
        }

        if ($upstream === null) {
            Log::error('Failed to stream book PDF after retries', [
                'book_id' => $book->id,
                'url' => $fileUrl,
                'error' => $lastError?->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'The book file is temporarily unavailable. Please try again shortly.',
            ], 502);
        }

        $body = $upstream->getBody();
        $contentType = $upstream->getHeaderLine('Content-Type') ?: 'application/pdf';
        $contentLength = $upstream->getHeaderLine('Content-Length');

        $headers = [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="'.$this->safeFilename($book->title).'.pdf"',
            'Cache-Control' => 'public, max-age=86400',
        ];
        if ($contentLength !== '') {
            $headers['Content-Length'] = $contentLength;
        }

        return response()->stream(function () use ($body) {
            while (! $body->eof()) {
                echo $body->read(65536); // 64KB chunks
                flush();
            }
            $body->close();
        }, 200, $headers);
    }

    /**
     * Strip characters that would break a Content-Disposition filename.
     */
    private function safeFilename(string $title): string
    {
        $safe = preg_replace('/[^A-Za-z0-9 _.-]/', '', $title);

        return trim($safe) !== '' ? trim($safe) : 'book';
    }
}
