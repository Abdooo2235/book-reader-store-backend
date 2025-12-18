<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Register a new customer
     */
    public function signup(Request $request)
    {
        // Validate the input data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:250', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => 'required',
            'address' => 'required',
            'email' => ['required', 'email'],
        ]);

        // Set user type as customer
        $data['type'] = 'customer';

        // Create the user and customer records
        // TODO: Wrap in DB transaction for safety
        $user = User::create($data);
        $user->customer()->create($data);

        // Return success response
        return response()->json([
            'message' => 'You have signed up successfully!'
        ], 201);
    }

    /**
     * Update customer profile
     */
    public function editProfile(Request $request)
    {
        // Get the current user and their customer profile
        $user = $request->user();
        $customer = $user->customer;

        // Validate the input
        $data = $request->validate([
            'phone_number' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'max:250','unique:customers,email,'] . $customer->id,
            'address' => ['sometimes', 'string', 'min:8'],
        ]);

        // Update the customer profile
        $customer->fill($data);
        $customer->save();

        // Return success response with updated data
        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => [
                'phone_number' => $customer->phone_number,
                'email' => $customer->email,
                'address' => $customer->address,
            ]
        ]);
    }
}