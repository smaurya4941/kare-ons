<?php

namespace App\Models;

use App\Services\CacheService;
use App\Support\HasIndexableScope;
use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;
    use HasIndexableScope;
    use LogsActivity;

    /** Long-form fields excluded from the audit diff. */
    protected array $activityLogIgnore = ['content', 'excerpt', 'meta_description', 'seo_description'];

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
        'seo_title',
        'seo_description',
        'is_indexable',
        'published_at',
    ];

    protected static function booted()
    {
        static::saved(fn () => CacheService::flushBlogs());
        static::deleted(fn () => CacheService::flushBlogs());
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_indexable' => 'boolean',
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

    /**
     * Baseline "publicly visible" constraint for HasIndexableScope — a blog
     * post is visible when it's published, not merely `status = true`.
     */
    protected function applyActiveConstraint(Builder $query): void
    {
        $query->published();
    }
}
