<?php

namespace App\Models;

use App\Services\CacheService;
use App\Support\HasIndexableScope;
use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    use HasIndexableScope;
    use LogsActivity;

    protected $guarded = ['id'];

    protected array $activityLogIgnore = ['description', 'seo_description'];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_indexable' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saved(fn () => CacheService::flushCategories());
        static::deleted(fn () => CacheService::flushCategories());
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

    // scopeIndexable() is provided by App\Support\HasIndexableScope.
}
