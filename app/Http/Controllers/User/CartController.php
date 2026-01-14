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

    // Calculate total price
    $total = $cart->items->sum(fn($item) => $item->book->price ?? 0);

    return response()->json([
      'success' => true,
      'data' => [
        'cart' => $cart,
        'total_items' => $cart->total_items,
        'total_price' => round($total, 2),
      ]
    ]);
  }

  public function add(Request $request, Book $book): JsonResponse
  {
    if (!$book->isApproved()) {
      return response()->json(['success' => false, 'message' => 'Book not available.'], 400);
    }

    $user = $request->user();

    if ($user->hasOrderedBook($book->id)) {
      return response()->json(['success' => false, 'message' => 'Already ordered.'], 400);
    }

    if ($user->cart->hasBook($book->id)) {
      return response()->json(['success' => false, 'message' => 'Already in cart.'], 400);
    }

    $user->cart->addBook($book->id);
    $user->cart->load('items.book.category');

    $total = $user->cart->items->sum(fn($item) => $item->book->price ?? 0);

    return response()->json([
      'success' => true,
      'message' => 'Added to cart.',
      'data' => [
        'cart' => $user->cart,
        'total_price' => round($total, 2),
      ]
    ]);
  }

  public function remove(Request $request, Book $book): JsonResponse
  {
    $cart = $request->user()->cart;

    if (!$cart->hasBook($book->id)) {
      return response()->json(['success' => false, 'message' => 'Not in cart.'], 400);
    }

    $cart->removeBook($book->id);
    $cart->load('items.book.category');

    $total = $cart->items->sum(fn($item) => $item->book->price ?? 0);

    return response()->json([
      'success' => true,
      'message' => 'Removed.',
      'data' => [
        'cart' => $cart,
        'total_price' => round($total, 2),
      ]
    ]);
  }

  public function checkout(Request $request): JsonResponse
  {
    $user = $request->user();
    $cart = $user->cart;
    $cart->load('items.book');

    // Check cart is not empty
    if ($cart->items()->count() === 0) {
      return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
    }

    // Calculate total price
    $total = $cart->items->sum(fn($item) => $item->book->price ?? 0);

    // Check sufficient balance
    if ($user->balance < $total) {
      return response()->json([
        'success' => false,
        'message' => 'Insufficient balance.',
        'data' => [
          'required' => round($total, 2),
          'balance' => round($user->balance, 2),
          'shortage' => round($total - $user->balance, 2),
        ]
      ], 400);
    }

    // Create order (this also deducts from wallet)
    $order = Order::createFromCart($cart, $total);

    return response()->json([
      'success' => true,
      'message' => 'Order placed successfully.',
      'data' => [
        'order' => $order->load('items.book'),
        'total_paid' => round($total, 2),
        'new_balance' => round($user->fresh()->balance, 2),
      ]
    ]);
  }
}
