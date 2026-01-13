<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
  use HasFactory;

  protected $fillable = ['user_id', 'name', 'is_default'];

  protected $casts = ['is_default' => 'boolean'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function books()
  {
    return $this->belongsToMany(Book::class, 'collection_book')->withPivot('added_at');
  }

  public function hasBook(int $bookId): bool
  {
    return $this->books()->where('book_id', $bookId)->exists();
  }
  public function addBook(int $bookId): void
  {
    if (!$this->hasBook($bookId)) $this->books()->attach($bookId);
  }
  public function removeBook(int $bookId): void
  {
    $this->books()->detach($bookId);
  }
}
