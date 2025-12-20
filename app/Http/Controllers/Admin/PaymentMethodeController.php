<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = PaymentMethod::all();
        return response()->json([
            'payments' => $payments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required'],
        ]);

        $payment = PaymentMethod::create($input);

        return response()->json([
            'message' => 'Payment method created successfully',
            'payment' => $payment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = PaymentMethod::findOrFail($id);

        return response()->json([
            'payment' => $payment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->validate([
            'name'=> ['required'],
        ]);

        $payment = PaymentMethod::findOrFail($id);
        $payment->update($input);

        return response()->json([
            'message' => 'Payment method updated successfully',
            'payment' => $payment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = PaymentMethod::findOrFail($id);
        $payment->delete();

        return response()->json([
            'message' => 'Payment method deleted successfully'
        ], 200);
    }
}
