<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = ['id'];

    protected array $activityLogIgnore = ['description', 'seo_description'];

    protected function casts(): array
    {
        return [
            'status'       => 'boolean',
            'is_indexable' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushCategories());
        static::deleted(fn () => \App\Services\CacheService::flushCategories());
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Active categories that are allowed to be indexed by search engines.
     */
    public function scopeIndexable($query)
    {
        return $query->where('status', true)->where('is_indexable', true);
    }
}