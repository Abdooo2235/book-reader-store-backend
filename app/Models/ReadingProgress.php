<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingProgress extends Model
{
  use HasFactory;

  protected $table = 'reading_progress';
  protected $fillable = ['user_id', 'book_id', 'progress_percentage', 'last_page'];
  protected $casts = ['progress_percentage' => 'decimal:2'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function book()
  {
    return $this->belongsTo(Book::class);
  }

  public static function getOrCreate(int $userId, int $bookId): self
  {
    return static::firstOrCreate(['user_id' => $userId, 'book_id' => $bookId]);
  }

  public function updateProgress(int $lastPage, int $totalPages): void
  {
    $this->last_page = $lastPage;
    $this->progress_percentage = $totalPages > 0 ? min(100, ($lastPage / $totalPages) * 100) : 0;
    $this->save();
  }
}
