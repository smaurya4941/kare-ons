<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;
    use LogsActivity;

    /** Long-form fields excluded from the audit diff. */
    protected array $activityLogIgnore = ['content', 'excerpt'];

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushBlogs());
        static::deleted(fn () => \App\Services\CacheService::flushBlogs());
    }

    protected function casts(): array
    {
        return [
            'status'       => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope to only published/active posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }
}
