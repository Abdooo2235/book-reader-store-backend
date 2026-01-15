<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    return response()->json(['success' => true, 'data' => $request->user()->readingProgress()->with('book.category')->orderBy('updated_at', 'desc')->get()]);
  }

  public function show(Request $request, Book $book): JsonResponse
  {
    $progress = $request->user()->readingProgress()->where('book_id', $book->id)->first();
    return response()->json(['success' => true, 'data' => $progress ?? ['book_id' => $book->id, 'progress_percentage' => 0, 'last_page' => 0]]);
  }

  public function update(Request $request, Book $book): JsonResponse
  {
    $user = $request->user();

    // For a FREE book store: Allow progress for any book in user's collections
    // or any approved book (since all approved books are free to read)
    $hasInCollection = $user->collections()
      ->whereHas('books', fn($q) => $q->where('books.id', $book->id))
      ->exists();

    if (!$hasInCollection && !$book->isApproved()) {
      return response()->json(['success' => false, 'message' => 'Book not available.'], 403);
    }

    // Auto-add book to Reading collection if not in any collection
    if (!$hasInCollection && $book->isApproved()) {
      $readingCollection = $user->collections()->where('name', 'Reading')->first();
      if ($readingCollection) {
        $readingCollection->addBook($book->id);
      }
    }

    $lastPage = $request->validate(['last_page' => 'required|integer|min:0'])['last_page'];
    $progress = ReadingProgress::getOrCreate($user->id, $book->id);
    $progress->updateProgress($lastPage, $book->number_of_pages);
    return response()->json(['success' => true, 'message' => 'Progress updated.', 'data' => $progress]);
  }
}
