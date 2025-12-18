<?php

namespace App\Http\Controllers\Customer;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('user_id', $user_id)->get();
        return $orders;
    }
}