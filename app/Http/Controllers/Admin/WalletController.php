<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
  /**
   * Top up a user's wallet (Admin only)
   */
  public function topup(Request $request, User $user): JsonResponse
  {
    $validated = $request->validate([
      'amount' => 'required|numeric|min:0.01|max:10000',
      'description' => 'nullable|string|max:255',
    ]);

    $amount = $validated['amount'];
    $description = $validated['description'] ?? 'Admin top-up';

    // Add to wallet
    $user->increment('balance', $amount);

    // Create transaction record
    WalletTransaction::create([
      'user_id' => $user->id,
      'type' => 'credit',
      'amount' => $amount,
      'balance_after' => $user->fresh()->balance,
      'description' => $description,
      'reference_type' => 'topup',
    ]);

    return response()->json([
      'success' => true,
      'message' => "Added \${$amount} to user's wallet.",
      'data' => [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'amount_added' => round($amount, 2),
        'new_balance' => round($user->fresh()->balance, 2),
      ]
    ]);
  }

  /**
   * Get a user's wallet info (Admin view)
   */
  public function show(User $user): JsonResponse
  {
    $transactions = WalletTransaction::where('user_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->limit(50)
      ->get();

    return response()->json([
      'success' => true,
      'data' => [
        'user' => [
          'id' => $user->id,
          'name' => $user->name,
          'email' => $user->email,
          'balance' => round($user->balance, 2),
        ],
        'transactions' => $transactions,
      ]
    ]);
  }
}
