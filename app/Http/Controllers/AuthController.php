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
                'message' => 'The  provided credentials are incorrect.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'type' => 'bearer',
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

    public function editProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes','string','max:255'], 
            'username' => ['sometimes','string','max:250','unique:users,username,' . $user->id],
            'password' => ['sometimes','string','min:8'],
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }

        if (isset($validated['username'])) {
            $user->username = $validated['username'];
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'type' => $user->type,
            ]
        ]);
    }
}
