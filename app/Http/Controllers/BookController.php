<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    if (!$book->isApproved()) {
      /** @var \App\Models\User|null $user */
      $user = auth('sanctum')->user();

      if (!$user || ($book->created_by !== $user->id && $user->role !== 'admin')) {
        return response()->json([
          'success' => false,
          'message' => 'Book not found.',
        ], 404);
      }
    }

    $book->load([
      'category',
      'creator:id,name,email',
      'reviews' => fn($q) => $q->with('user:id,name')->latest()->limit(5),
    ]);

    $book->loadCount('reviews');

    return response()->json([
      'success' => true,
      'data' => $book,
    ]);
  }

  /**
   * Stream a book's PDF file.
   * This proxies external PDF files to avoid CORS issues.
   */
  public function stream(Book $book)
  {
    // Only allow approved books
    if (!$book->isApproved()) {
      abort(404, 'Book not found.');
    }

    $fileUrl = $book->book_file_url;

    if (!$fileUrl) {
      abort(404, 'Book file not available.');
    }

    try {
      // Fetch the PDF from the external URL
      $client = new \GuzzleHttp\Client([
        'timeout' => 60,
        'verify' => false, // For development - production should verify SSL
      ]);

      $response = $client->get($fileUrl, [
        'headers' => [
          'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
          'Accept' => 'application/pdf,*/*',
        ],
      ]);

      $contentType = $response->getHeader('Content-Type')[0] ?? 'application/pdf';
      $body = $response->getBody()->getContents();

      return response($body, 200, [
        'Content-Type' => $contentType,
        'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"',
        'Content-Length' => strlen($body),
        'Cache-Control' => 'public, max-age=86400',
      ]);
    } catch (\Exception $e) {
      Log::error('Failed to stream book PDF: ' . $e->getMessage());
      abort(502, 'Failed to fetch book file.');
    }
  }
}
