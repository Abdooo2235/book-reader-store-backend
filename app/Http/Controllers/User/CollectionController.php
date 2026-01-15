<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
  /**
   * Get user's collections.
   */
  public function index(Request $request): JsonResponse
  {
    $collections = $request->user()->collections()->withCount('books')->get();
    return response()->json(['success' => true, 'data' => $collections]);
  }

  /**
   * Create a new collection (shelf).
   */
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'name' => 'required|string|max:100',
    ]);

    $collection = $request->user()->collections()->create([
      'name' => $validated['name'],
      'is_default' => false,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Collection created.',
      'data' => $collection,
    ], 201);
  }

  /**
   * Get books in a collection.
   */
  public function books(Request $request, Collection $collection): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) {
      return response()->json(['success' => false, 'message' => 'Collection not found.'], 404);
    }

    $books = $collection->books()
      ->with(['category', 'readingProgress' => fn($q) => $q->where('user_id', $request->user()->id)])
      ->paginate($request->get('per_page', 15));

    return response()->json(['success' => true, 'data' => $books]);
  }

  /**
   * Add a book to a collection.
   * For a FREE book store - any approved book can be added.
   */
  public function addBook(Request $request, Collection $collection): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) {
      return response()->json(['success' => false, 'message' => 'Collection not found.'], 404);
    }

    $validated = $request->validate(['book_id' => 'required|exists:books,id']);
    $book = Book::find($validated['book_id']);

    // Book must be approved
    if (!$book->isApproved()) {
      return response()->json(['success' => false, 'message' => 'Book is not available.'], 400);
    }

    if ($collection->hasBook($book->id)) {
      return response()->json(['success' => false, 'message' => 'Book is already in this collection.'], 400);
    }

    $collection->addBook($book->id);

    return response()->json(['success' => true, 'message' => 'Book added to collection.']);
  }

  /**
   * Remove a book from a collection.
   */
  public function removeBook(Request $request, Collection $collection, Book $book): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) {
      return response()->json(['success' => false, 'message' => 'Collection not found.'], 404);
    }

    if (!$collection->hasBook($book->id)) {
      return response()->json(['success' => false, 'message' => 'Book is not in this collection.'], 400);
    }

    $collection->removeBook($book->id);

    return response()->json(['success' => true, 'message' => 'Book removed from collection.']);
  }

  /**
   * Delete a collection (non-default only).
   */
  public function destroy(Request $request, Collection $collection): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) {
      return response()->json(['success' => false, 'message' => 'Collection not found.'], 404);
    }

    if ($collection->is_default) {
      return response()->json(['success' => false, 'message' => 'Cannot delete default collections.'], 400);
    }

    $collection->delete();

    return response()->json(['success' => true, 'message' => 'Collection deleted.']);
  }
}
