<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class LibraryController extends Controller
{
  /**
   * Display user's library (ordered books).
   * 
   * Supports:
   * - Filtering: ?filter[category_id]=1&filter[title]=flutter
   * - Sorting: ?sort=-created_at
   */
  public function index(Request $request): JsonResponse
  {
    $user = $request->user();
    $downloadedBookIds = $user->downloadedBookIds();

    if (empty($downloadedBookIds)) {
      return response()->json([
        'success' => true,
        'data' => [
          'current_page' => 1,
          'data' => [],
          'total' => 0,
        ],
      ]);
    }

    $books = QueryBuilder::for(Book::class)
      ->whereIn('id', $downloadedBookIds)
      ->allowedFilters([
        AllowedFilter::exact('category_id'),
        AllowedFilter::partial('title'),
        AllowedFilter::partial('author'),
      ])
      ->allowedSorts([
        AllowedSort::field('title'),
        AllowedSort::field('created_at'),
      ])
      ->defaultSort('-created_at')
      ->with([
        'category',
        'readingProgress' => fn($q) => $q->where('user_id', $user->id),
      ])
      ->paginate($request->get('per_page', 15))
      ->withQueryString();

    return response()->json([
      'success' => true,
      'data' => $books,
    ]);
  }

  /**
   * Get download URL for a book.
   */
  public function download(Request $request, Book $book): JsonResponse
  {
    // Verify user has ordered this book
    if (!$request->user()->hasOrderedBook($book->id)) {
      return response()->json([
        'success' => false,
        'message' => 'You must order this book first.',
      ], 403);
    }

    $fileUrl = $book->book_file_url;

    if (!$fileUrl) {
      return response()->json([
        'success' => false,
        'message' => 'Book file is not available.',
      ], 404);
    }

    return response()->json([
      'success' => true,
      'data' => [
        'file_url' => $fileUrl,
        'file_type' => $book->file_type,
        'title' => $book->title,
        'number_of_pages' => $book->number_of_pages,
      ],
    ]);
  }

  /**
   * Add a book to favorites.
   */
  public function favorite(Request $request, Book $book): JsonResponse
  {
    $user = $request->user();

    if (!$user->hasOrderedBook($book->id)) {
      return response()->json([
        'success' => false,
        'message' => 'You must order this book first.',
      ], 403);
    }

    $favoritesCollection = $user->collections()->where('name', 'Favorites')->first();

    if (!$favoritesCollection) {
      $favoritesCollection = $user->collections()->create(['name' => 'Favorites', 'is_default' => true]);
    }

    if ($favoritesCollection->hasBook($book->id)) {
      return response()->json([
        'success' => false,
        'message' => 'Book is already in favorites.',
      ], 400);
    }

    $favoritesCollection->addBook($book->id);

    return response()->json([
      'success' => true,
      'message' => 'Book added to favorites.',
    ]);
  }

  /**
   * Remove a book from favorites.
   */
  public function unfavorite(Request $request, Book $book): JsonResponse
  {
    $user = $request->user();
    $favoritesCollection = $user->collections()->where('name', 'Favorites')->first();

    if (!$favoritesCollection || !$favoritesCollection->hasBook($book->id)) {
      return response()->json([
        'success' => false,
        'message' => 'Book is not in favorites.',
      ], 400);
    }

    $favoritesCollection->removeBook($book->id);

    return response()->json([
      'success' => true,
      'message' => 'Book removed from favorites.',
    ]);
  }

  /**
   * Get all favorite books.
   */
  public function favorites(Request $request): JsonResponse
  {
    $user = $request->user();
    $favoritesCollection = $user->collections()->where('name', 'Favorites')->first();

    if (!$favoritesCollection) {
      return response()->json([
        'success' => true,
        'data' => [],
      ]);
    }

    $books = $favoritesCollection->books()
      ->with(['category', 'readingProgress' => fn($q) => $q->where('user_id', $user->id)])
      ->paginate($request->get('per_page', 15));

    return response()->json([
      'success' => true,
      'data' => $books,
    ]);
  }
}
