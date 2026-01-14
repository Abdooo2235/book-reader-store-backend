<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
  /**
   * Get wallet balance and transaction history
   */
  public function index(Request $request): JsonResponse
  {
    $user = $request->user();

    $transactions = WalletTransaction::where('user_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->limit(50)
      ->get();

    return response()->json([
      'success' => true,
      'data' => [
        'balance' => round($user->balance, 2),
        'transactions' => $transactions,
      ]
    ]);
  }
}
