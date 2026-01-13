<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    public function books()
    {
        return $this->belongsToMany(Book::class, 'cart_items');
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items()->count();
    }
    public function hasBook(int $bookId): bool
    {
        return $this->items()->where('book_id', $bookId)->exists();
    }
    public function addBook(int $bookId): void
    {
        if (!$this->hasBook($bookId)) $this->items()->create(['book_id' => $bookId]);
    }
    public function removeBook(int $bookId): void
    {
        $this->items()->where('book_id', $bookId)->delete();
    }
    public function clear(): void
    {
        $this->items()->delete();
    }
}
