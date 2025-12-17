<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        return new CartResource($cart);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $book_id)
    {
        $user = Auth::user();

        // if user not have a cart
        $cart = Cart::where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id,
            'payment_method_id' => PaymentMethods::first()->id,
            'address' => $user->customer->address
        ]);


        $cartItem = CartItem::where('cart_id', $cart->id)->where('book_id', $book_id)->first();

        if ($cartItem) {
            $cartItem->update([
                'qty' => $cartItem->qty + 1
            ]);
        } else {
            // add item to the cart
            $cart->items()->create([
                'book_id' => $book_id,
                'qty' => 1
            ]);
        }

        return response()->json([
            'message' => 'item added'
        ]);
    }

    public function checkout() {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        $total = $cart->totalCart();
        $order = Order::create([
            'customer_id'=> $cart->user_id,
            'payment_method_id'=> $cart->payment_method_id,
            'address'=> $cart->address,
            'total'=> $total,
            'status'=> 'pending'
        ]);

        foreach($cart->items as $item) {

            $order->items()->create([
                'order_id'=> $order->id,
                'book_id'=>
                'quantity'=>
                'price'=> $
            ]);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
