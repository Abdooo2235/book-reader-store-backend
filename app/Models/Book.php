<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title',
        'author',
        'release_date',
        'description',
        'category_id',
        'cover_image',
        'cover_type',
        'file_type',
        'file_url',
        'number_of_pages',
        'status',
        'rejection_reason',
        'average_rating',
        'created_by',
    ];

    protected $casts = [
        'release_date' => 'date',
        'average_rating' => 'decimal:2',
        'number_of_pages' => 'integer',
    ];

    protected $appends = ['cover_url', 'book_file_url'];

    // ============================================================================
    // RELATIONSHIPS
    // ============================================================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }

    // ============================================================================
    // SCOPES
    // ============================================================================

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ============================================================================
    // STATUS HELPERS
    // ============================================================================

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);
    }

    public function reject(?string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    // ============================================================================
    // RATING
    // ============================================================================

    public function updateAverageRating(): void
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->save();
    }

    // ============================================================================
    // SPATIE MEDIA LIBRARY
    // ============================================================================

    /**
     * Register media collections for the book.
     * - cover: Single cover image (jpeg, png, webp)
     * - book_file: Single book file (pdf, epub)
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            ]);

        $this->addMediaCollection('book_file')
            ->singleFile()
            ->acceptsMimeTypes([
                'application/pdf',
                'application/epub+zip',
            ]);
    }

    /**
     * Register media conversions (optional thumbnails).
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(300)
            ->nonQueued()
            ->performOnCollections('cover');
    }

    // ============================================================================
    // ACCESSORS
    // ============================================================================

    /**
     * Get cover image URL (supports both upload and external URL).
     */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_type === 'url' && $this->cover_image) {
            return $this->cover_image;
        }

        $media = $this->getFirstMedia('cover');
        if ($media) {
            return $media->getUrl();
        }

        return null;
    }

    /**
     * Get book file URL.
     */
    public function getBookFileUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('book_file');
        if ($media) {
            return $media->getUrl();
        }

        if ($this->file_url) {
            return asset('storage/' . $this->file_url);
        }

        return null;
    }

    /**
     * Get cover thumbnail URL.
     */
    public function getCoverThumbUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('cover');
        if ($media) {
            return $media->getUrl('thumb');
        }

        return $this->cover_url;
    }
}
