<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'address',
        'total',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(Customer::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethods::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
