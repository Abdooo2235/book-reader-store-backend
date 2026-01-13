<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_items'];

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

    public static function createFromCart(Cart $cart): Order
    {
        $order = static::create([
            'user_id' => $cart->user_id,
            'total_items' => $cart->total_items,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create(['book_id' => $item->book_id]);
        }

        $cart->clear();
        return $order;
    }
}
