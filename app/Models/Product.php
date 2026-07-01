<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('homepage_data');
        });

        static::deleted(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('homepage_data');
        });
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
     * Get the effective selling price (sale price if set, otherwise regular price).
     */
    public function getEffectivePriceAttribute(): string
    {
        return $this->sale_price ?? $this->price;
    }
}