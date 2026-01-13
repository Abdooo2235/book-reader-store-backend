<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    $orders = $request->user()->orders()->with('items.book.category')->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));
    return response()->json(['success' => true, 'data' => $orders]);
  }

  public function show(Request $request, int $orderId): JsonResponse
  {
    $order = $request->user()->orders()->with('items.book.category')->findOrFail($orderId);
    return response()->json(['success' => true, 'data' => $order]);
  }
}
