<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    return response()->json(['success' => true, 'data' => $request->user()->collections()->withCount('books')->get()]);
  }

  public function books(Request $request, Collection $collection): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) return response()->json(['success' => false, 'message' => 'Not found.'], 404);
    $books = $collection->books()->with(['category', 'readingProgress' => fn($q) => $q->where('user_id', $request->user()->id)])->paginate($request->get('per_page', 15));
    return response()->json(['success' => true, 'data' => $books]);
  }

  public function addBook(Request $request, Collection $collection): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) return response()->json(['success' => false, 'message' => 'Not found.'], 404);
    $bookId = $request->validate(['book_id' => 'required|exists:books,id'])['book_id'];
    if (!$request->user()->hasOrderedBook($bookId)) return response()->json(['success' => false, 'message' => 'Must order first.'], 403);
    if ($collection->hasBook($bookId)) return response()->json(['success' => false, 'message' => 'Already in collection.'], 400);
    $collection->addBook($bookId);
    return response()->json(['success' => true, 'message' => 'Added.']);
  }

  public function removeBook(Request $request, Collection $collection, Book $book): JsonResponse
  {
    if ($collection->user_id !== $request->user()->id) return response()->json(['success' => false, 'message' => 'Not found.'], 404);
    if (!$collection->hasBook($book->id)) return response()->json(['success' => false, 'message' => 'Not in collection.'], 400);
    $collection->removeBook($book->id);
    return response()->json(['success' => true, 'message' => 'Removed.']);
  }
}
