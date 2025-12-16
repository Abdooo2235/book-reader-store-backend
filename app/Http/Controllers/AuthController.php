<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $inputs = $request->validate([
            'username' => ['required', 'string', 'min:4', 'max:250'],
            'password' => ['required', 'min:8']
        ]);

        $user = User::where('username', $inputs['username'])->first();

        if (!$user || !Hash::check($inputs['password'], $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'type' => 'bearer',
            995593
        ]);
    }
}
