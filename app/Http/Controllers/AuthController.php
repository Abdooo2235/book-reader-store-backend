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
        ]);
    }
    public function register(Request $request)
    {
        $inputs = $request->validate([
            'username' => ['required', 'string', 'min:4', 'max:250'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return response()->json([
            'message' => 'Creaet account Successful',
            'user' => $user
        ]);
    }



    public function logout(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'logout account Successful'
        ]);
    }
}
