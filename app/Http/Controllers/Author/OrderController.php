<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $myBookIds = $user->books()->pluck('id');

        // Find orders that contain any of the author's books
        $orders = Order::whereHas('items', function ($query) use ($myBookIds) {
            $query->whereIn('book_id', $myBookIds);
        })->with('items')->get();

        return response()->json([
            'orders' => $orders,
            'message' => 'Orders found'
        ], 200);
    }
}
