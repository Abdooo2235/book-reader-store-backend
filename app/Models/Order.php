<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_items', 'total'];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'order_items');
    }

    /**
     * Create order from cart with wallet deduction
     */
    public static function createFromCart(Cart $cart, float $total): Order
    {
        $user = $cart->user;

        // Deduct from wallet
        $user->decrement('balance', $total);

        // Create wallet transaction
        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => $total,
            'balance_after' => $user->fresh()->balance,
            'description' => 'Book purchase',
            'reference_type' => 'order',
        ]);

        // Create order
        $order = static::create([
            'user_id' => $cart->user_id,
            'total_items' => $cart->total_items,
            'total' => $total,
        ]);

        // Move cart items to order
        foreach ($cart->items as $item) {
            $order->items()->create(['book_id' => $item->book_id]);
        }

        // Update wallet transaction with order ID
        WalletTransaction::where('user_id', $user->id)
            ->where('reference_type', 'order')
            ->whereNull('reference_id')
            ->latest()
            ->first()
            ?->update(['reference_id' => $order->id]);

        $cart->clear();
        return $order;
    }
}
