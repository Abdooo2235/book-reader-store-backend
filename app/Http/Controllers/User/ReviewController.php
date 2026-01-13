<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
  public function index(Book $book): JsonResponse
  {
    $reviews = $book->reviews()->with('user:id,name')->orderBy('created_at', 'desc')->paginate(15);
    return response()->json(['success' => true, 'data' => ['reviews' => $reviews, 'average_rating' => $book->average_rating, 'total_reviews' => $book->reviews()->count()]]);
  }

  public function store(Request $request, Book $book): JsonResponse
  {
    $validated = $request->validate(['rating' => 'required|integer|min:1|max:5', 'review_text' => 'nullable|string|max:1000']);
    if (!$request->user()->hasOrderedBook($book->id)) return response()->json(['success' => false, 'message' => 'Must order first.'], 403);
    $review = Review::updateOrCreate(['user_id' => $request->user()->id, 'book_id' => $book->id], ['rating' => $validated['rating'], 'review_text' => $validated['review_text'] ?? null]);
    return response()->json(['success' => true, 'message' => 'Review saved.', 'data' => $review->load('user:id,name')]);
  }

  public function update(Request $request, Review $review): JsonResponse
  {
    if ($review->user_id !== $request->user()->id) return response()->json(['success' => false, 'message' => 'Can only update own reviews.'], 403);
    $validated = $request->validate(['rating' => 'required|integer|min:1|max:5', 'review_text' => 'nullable|string|max:1000']);
    $review->update(['rating' => $validated['rating'], 'review_text' => $validated['review_text'] ?? null]);
    return response()->json(['success' => true, 'message' => 'Review updated.', 'data' => $review->load('user:id,name')]);
  }

  public function destroy(Request $request, Review $review): JsonResponse
  {
    if ($review->user_id !== $request->user()->id) return response()->json(['success' => false, 'message' => 'Can only delete own reviews.'], 403);
    $review->delete();
    return response()->json(['success' => true, 'message' => 'Review deleted.']);
  }
}
