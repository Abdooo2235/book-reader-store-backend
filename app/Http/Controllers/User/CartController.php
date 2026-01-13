<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    $cart = $request->user()->cart;
    $cart->load('items.book.category');
    return response()->json(['success' => true, 'data' => ['cart' => $cart, 'total_items' => $cart->total_items]]);
  }

  public function add(Request $request, Book $book): JsonResponse
  {
    if (!$book->isApproved()) return response()->json(['success' => false, 'message' => 'Book not available.'], 400);
    $user = $request->user();
    if ($user->hasOrderedBook($book->id)) return response()->json(['success' => false, 'message' => 'Already ordered.'], 400);
    if ($user->cart->hasBook($book->id)) return response()->json(['success' => false, 'message' => 'Already in cart.'], 400);
    $user->cart->addBook($book->id);
    $user->cart->load('items.book.category');
    return response()->json(['success' => true, 'message' => 'Added to cart.', 'data' => $user->cart]);
  }

  public function remove(Request $request, Book $book): JsonResponse
  {
    $cart = $request->user()->cart;
    if (!$cart->hasBook($book->id)) return response()->json(['success' => false, 'message' => 'Not in cart.'], 400);
    $cart->removeBook($book->id);
    $cart->load('items.book.category');
    return response()->json(['success' => true, 'message' => 'Removed.', 'data' => $cart]);
  }

  public function checkout(Request $request): JsonResponse
  {
    $cart = $request->user()->cart;
    if ($cart->items()->count() === 0) return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
    $order = Order::createFromCart($cart);
    return response()->json(['success' => true, 'message' => 'Order placed.', 'data' => $order->load('items.book')]);
  }
}
