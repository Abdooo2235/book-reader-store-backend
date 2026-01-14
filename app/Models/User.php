<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role', 'balance', 'avatar_path'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->cart()->create([]);
            foreach (['Reading', 'Already Read', 'Planning', 'Favorites'] as $name) {
                $user->collections()->create(['name' => $name, 'is_default' => true]);
            }
            $user->preferences()->create([]);
        });
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }
    public function submittedBooks()
    {
        return $this->hasMany(Book::class, 'created_by');
    }

    public function downloadedBookIds(): array
    {
        return OrderItem::whereIn('order_id', $this->orders()->pluck('id'))->pluck('book_id')->toArray();
    }

    public function hasOrderedBook(int $bookId): bool
    {
        return in_array($bookId, $this->downloadedBookIds());
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? asset('storage/' . $this->avatar_path) : null;
    }

    public function getTotalBooksDownloadedAttribute(): int
    {
        return count($this->downloadedBookIds());
    }
    public function getBooksCurrentlyReadingAttribute(): int
    {
        return $this->readingProgress()->where('progress_percentage', '>', 0)->where('progress_percentage', '<', 100)->count();
    }
    public function getBooksCompletedAttribute(): int
    {
        return $this->readingProgress()->where('progress_percentage', '>=', 100)->count();
    }
    public function getReviewsWrittenAttribute(): int
    {
        return $this->reviews()->count();
    }
    public function getReadingStatsAttribute(): array
    {
        return [
            'total_books_downloaded' => $this->total_books_downloaded,
            'books_currently_reading' => $this->books_currently_reading,
            'books_completed' => $this->books_completed,
            'reviews_written' => $this->reviews_written,
        ];
    }
}
