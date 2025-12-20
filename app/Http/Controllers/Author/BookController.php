<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequestAuthor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function showAll()
    {
        $books = Book::all();
        return $books;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return User::findOrFail($user->id)->load('books');
    }

    public function addRequest(Request $request)
    {
        $user_id = Auth::user()->id;

        $inputs = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'id' => ['required'],
        ]);

        $inputs['user_id'] = $user_id;
        $bookRequestAuthor = BookRequestAuthor::create($inputs);
        return response()->json([
            'message' => 'Book request added successfully',
            'bookRequestAuthor' => $bookRequestAuthor
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function getRequests()
    {
        $user = Auth::user();

        $booksWithRequests = Book::where('owner_id', $user->id)->whereHas('requests') //Give me just the requests
            ->with('requests') //Give me who send the request
            ->get();

        return response()->json([
            'status' => true,
            'books' => $booksWithRequests
        ], 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $inputs = $request->validate([
            'title' => ['required', 'max:255'],
            'publish_year' => ['required', 'min:4', 'max:4'],
            'price' => ['required', 'decimal:1,50'],
            'isbn' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $inputs['owner_id'] = $user->id;
        $book = Book::create($inputs);
        $user->books()->attach($book->id); //Connect this user with this book

        return response()->json([
            'message' => 'Book stored successfully',
            'book' => $book
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function updateStock(Request $request, $book_id)
    {
        $user = Auth::user();
        // Check if this book belongs to the user
        $isMyBook = $user->books()->pluck('id')->contains($book_id);

        if (!$isMyBook) {
            return response()->json([
                'message' => 'This book is not yours to update'
            ], 401);
        }

        $book = Book::findOrFail($book_id);
        $book->qty = $request->qty;
        $book->save();

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        // Check if this book belongs to the user
        $isMyBook = $user->books()->pluck('id')->contains($id);

        if (!$isMyBook) {
            return response()->json([
                'message' => 'This book is not yours to update'
            ], 401);
        }

        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:250|unique:books,isbn,' . $book->id,
            'publish_year' => 'sometimes|max:4|min:4',
            'price' => 'sometimes|decimal:1,50',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $book->fill($validated);
        $book->save();

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }
}
