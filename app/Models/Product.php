<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = ['id'];

    /** Noisy/large columns to keep out of the audit diff. */
    protected array $activityLogIgnore = ['description', 'short_description', 'meta_description', 'seo_description'];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushProducts());
        static::deleted(fn () => \App\Services\CacheService::flushProducts());
    }

    /**
     * Cast boolean and decimal fields to their native PHP types.
     * This is critical for status badge comparisons in Blade views.
     */
    protected function casts(): array
    {
        return [
            'status'   => 'boolean',
            'featured' => 'boolean', // old
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_trending' => 'boolean',
            'is_indexable' => 'boolean',
            'price'    => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function reviews(): HasMany
    {
        // Only show approved reviews
        return $this->hasMany(Review::class)->where('status', true);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Active products that are allowed to be indexed by search engines.
     * Used by the sitemap and anywhere else that must not surface
     * noindexed/draft products publicly.
     */
    public function scopeIndexable($query)
    {
        return $query->where('status', true)->where('is_indexable', true);
    }

    /**
     * Get the effective selling price (sale price if set, otherwise regular price).
     */
    public function getEffectivePriceAttribute(): string
    {
        return $this->sale_price ?? $this->price;
    }
}