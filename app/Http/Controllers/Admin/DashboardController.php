<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
  public function stats(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'data' => [
        'total_users' => User::where('role', 'user')->count(),
        'total_books' => Book::approved()->count(),
        'pending_books' => Book::pending()->count(),
        'total_categories' => Category::count(),
        'total_orders' => Order::count(),
        'recent_books' => Book::with('category', 'creator')->orderBy('created_at', 'desc')->take(5)->get(),
      ],
    ]);
  }
}
