<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  use HasFactory;

  protected $fillable = ['user_id', 'book_id', 'rating', 'review_text'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function book()
  {
    return $this->belongsTo(Book::class);
  }

  protected static function booted(): void
  {
    static::saved(fn(Review $r) => $r->book->updateAverageRating());
    static::deleted(fn(Review $r) => $r->book->updateAverageRating());
  }
}
