<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
  /**
   * Display a listing of all books (including pending).
   * 
   * Supports:
   * - Filtering: ?filter[status]=pending&filter[category_id]=1
   * - Sorting: ?sort=-created_at
   * - Includes: ?include=category,creator
   */
  public function index(Request $request): JsonResponse
  {
    $books = QueryBuilder::for(Book::class)
      ->allowedFilters([
        AllowedFilter::exact('status'),
        AllowedFilter::exact('category_id'),
        AllowedFilter::partial('title'),
        AllowedFilter::partial('author'),
        AllowedFilter::exact('file_type'),
        AllowedFilter::exact('created_by'),
      ])
      ->allowedSorts([
        AllowedSort::field('title'),
        AllowedSort::field('created_at'),
        AllowedSort::field('status'),
        AllowedSort::field('average_rating'),
      ])
      ->defaultSort('-created_at')
      ->allowedIncludes([
        AllowedInclude::relationship('category'),
        AllowedInclude::relationship('creator'),
        AllowedInclude::count('reviews'),
      ])
      ->with(['category', 'creator:id,name,email'])
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
  public function show(Book $book): JsonResponse
  {
    $book->load([
      'category',
      'creator:id,name,email',
      'reviews' => fn($q) => $q->with('user:id,name')->latest(),
    ]);

    return response()->json([
      'success' => true,
      'data' => $book,
    ]);
  }

  /**
   * Update a book.
   */
  public function update(Request $request, Book $book): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'title' => 'sometimes|string|max:255',
      'author' => 'sometimes|string|max:255',
      'description' => 'sometimes|nullable|string|max:5000',
      'category_id' => 'sometimes|exists:categories,id',
      'release_date' => 'sometimes|nullable|date',
      'number_of_pages' => 'sometimes|integer|min:1|max:10000',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $validator->errors(),
      ], 422);
    }

    $book->update($validator->validated());

    return response()->json([
      'success' => true,
      'message' => 'Book updated successfully.',
      'data' => $book->load('category'),
    ]);
  }

  /**
   * Delete a book.
   */
  public function destroy(Book $book): JsonResponse
  {
    $book->delete();

    return response()->json([
      'success' => true,
      'message' => 'Book deleted successfully.',
    ]);
  }

  /**
   * Approve a pending book.
   */
  public function approve(Book $book): JsonResponse
  {
    if ($book->isApproved()) {
      return response()->json([
        'success' => false,
        'message' => 'Book is already approved.',
      ], 400);
    }

    $book->approve();

    return response()->json([
      'success' => true,
      'message' => 'Book approved successfully.',
      'data' => $book,
    ]);
  }

  /**
   * Reject a pending book.
   */
  public function reject(Request $request, Book $book): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'rejection_reason' => 'nullable|string|max:500',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $validator->errors(),
      ], 422);
    }

    $book->reject($request->input('rejection_reason'));

    return response()->json([
      'success' => true,
      'message' => 'Book rejected.',
      'data' => $book,
    ]);
  }
}
