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
   * Display user's library (books from all collections).
   * For a FREE book store - returns all books the user has added to collections.
   * 
   * Supports:
   * - Filtering: ?filter[category_id]=1&filter[title]=flutter
   * - Sorting: ?sort=-created_at
   */
  public function index(Request $request): JsonResponse
  {
    $user = $request->user();

    // Get all book IDs from user's collections
    $collectionBookIds = $user->collections()
      ->with('books:id')
      ->get()
      ->pluck('books')
      ->flatten()
      ->pluck('id')
      ->unique()
      ->values()
      ->toArray();

    if (empty($collectionBookIds)) {
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
      ->whereIn('id', $collectionBookIds)
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
   * Since this is a free book store, any approved book can be downloaded.
   */
  public function download(Request $request, Book $book): JsonResponse
  {
    // Verify book is approved
    if (!$book->isApproved()) {
      return response()->json([
        'success' => false,
        'message' => 'This book is not available for download.',
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
        'cover_url' => $book->cover_url,
      ],
    ]);
  }

  /**
   * Add a book to favorites.
   * Users can favorite ANY approved book (no purchase required).
   */
  public function favorite(Request $request, Book $book): JsonResponse
  {
    $user = $request->user();

    // Verify book is approved
    if (!$book->isApproved()) {
      return response()->json([
        'success' => false,
        'message' => 'This book is not available.',
      ], 400);
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
