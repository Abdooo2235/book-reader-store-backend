<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class MyBookController extends Controller
{
  /**
   * Display user's submitted books.
   * 
   * Supports:
   * - Filtering: ?filter[status]=pending
   * - Sorting: ?sort=-created_at
   */
  public function index(Request $request): JsonResponse
  {
    $books = QueryBuilder::for(Book::class)
      ->where('created_by', $request->user()->id)
      ->allowedFilters([
        AllowedFilter::exact('status'),
        AllowedFilter::exact('category_id'),
      ])
      ->allowedSorts([
        AllowedSort::field('title'),
        AllowedSort::field('created_at'),
        AllowedSort::field('status'),
      ])
      ->defaultSort('-created_at')
      ->with('category')
      ->paginate($request->get('per_page', 15))
      ->withQueryString();

    return response()->json([
      'success' => true,
      'data' => $books,
    ]);
  }

  /**
   * Delete a pending book submission.
   */
  public function destroy(Request $request, Book $book): JsonResponse
  {
    // Verify ownership
    if ($book->created_by !== $request->user()->id) {
      return response()->json([
        'success' => false,
        'message' => 'You can only delete your own books.',
      ], 403);
    }

    // Only pending books can be deleted
    if (!$book->isPending()) {
      return response()->json([
        'success' => false,
        'message' => 'You can only delete pending books.',
      ], 403);
    }

    // Clear associated media
    $book->clearMediaCollection('cover');
    $book->clearMediaCollection('book_file');

    $book->delete();

    return response()->json([
      'success' => true,
      'message' => 'Book deleted successfully.',
    ]);
  }
}
