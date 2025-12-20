<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::where('status', 'pending')->get();
        
        if ($order->count() == 0) {
            return response()->json([
                'message' => 'No pending orders'
            ], 404);
        }

        return response()->json([
            'message' => 'Pending orders',
            'orders' => $order
        ], 200);
    }

    public function updateStatus($order_id) {
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $order->updateStatus();
        return response()->json([
            'message' => 'Order status updated it to delivered'
        ], 200);
    }
}
