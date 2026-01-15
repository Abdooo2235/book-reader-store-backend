<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
  /**
   * Submit a new book for approval.
   * 
   * Uses Spatie MediaLibrary for file uploads:
   * - book_file: PDF/EPUB file (max 50MB)
   * - cover_image: Image file (max 2MB) 
   */
  public function store(Request $request): JsonResponse
  {
    try {
      // Log incoming request for debugging
      Log::info('Book submission started', [
        'has_book_file' => $request->hasFile('book_file'),
        'book_file_size' => $request->hasFile('book_file')
          ? $request->file('book_file')->getSize()
          : null,
        'title' => $request->input('title'),
      ]);

      // Validation rules
      $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'release_date' => 'nullable|date',
        'description' => 'nullable|string|max:5000',
        'category_id' => 'required|exists:categories,id',
        'file_type' => 'required|in:pdf,epub',
        'number_of_pages' => 'nullable|integer|min:1|max:10000',
        'book_file' => 'required|file|mimes:pdf,epub|max:51200', // 50MB
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB
        'cover_url' => 'nullable|url|max:500',
      ]);

      if ($validator->fails()) {
        Log::warning('Book submission validation failed', [
          'errors' => $validator->errors()->toArray(),
        ]);
        return response()->json([
          'success' => false,
          'message' => 'Validation failed.',
          'errors' => $validator->errors(),
        ], 422);
      }

      $validated = $validator->validated();

      // Create book instance
      $book = new Book([
        'title' => $validated['title'],
        'author' => $validated['author'],
        'release_date' => $validated['release_date'] ?? null,
        'description' => $validated['description'] ?? null,
        'category_id' => $validated['category_id'],
        'file_type' => $validated['file_type'],
        'number_of_pages' => $validated['number_of_pages'] ?? 0,
        'status' => 'pending',
        'created_by' => $request->user()->id,
      ]);

      // Handle cover image (upload or URL)
      if ($request->hasFile('cover_image')) {
        $book->cover_type = 'upload';
        $book->save();

        // Use Spatie MediaLibrary to handle cover upload
        $book->addMediaFromRequest('cover_image')
          ->usingFileName(time() . '_cover.' . $request->file('cover_image')->extension())
          ->toMediaCollection('cover');
      } elseif (!empty($validated['cover_url'])) {
        $book->cover_type = 'url';
        $book->cover_image = $validated['cover_url'];
        $book->save();
      } else {
        $book->save();
      }

      // Handle book file upload using Spatie MediaLibrary
      if ($request->hasFile('book_file')) {
        $book->addMediaFromRequest('book_file')
          ->usingFileName(time() . '_book.' . $request->file('book_file')->extension())
          ->toMediaCollection('book_file');

        Log::info('Book file uploaded successfully', [
          'book_id' => $book->id,
          'file_url' => $book->book_file_url,
        ]);
      }

      $book->load('category');

      return response()->json([
        'success' => true,
        'message' => 'Book submitted successfully. Waiting for admin approval.',
        'data' => $book,
      ], 201);
    } catch (\Exception $e) {
      Log::error('Book submission failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'The book file failed to upload. Error: ' . $e->getMessage(),
      ], 500);
    }
  }
}
