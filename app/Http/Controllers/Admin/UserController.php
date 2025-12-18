<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('type', '!=', 'admin')->get();
        return $users;

    }

    public function block($user_id)
    {
        $user = User::where('type', '!=', 'admin')
            ->where('id', $user_id)
            ->firstOrFail();

        $user->block();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'user blocked'
        ]);
    }

    public function unblock($user_id)
    {
        $user = User::where('type', '!=', 'admin')
            ->where('id', $user_id)
            ->firstOrFail();

        $user->approve();
        return response()->json([
            'message' => 'user unblocked'
        ]);
    }
}