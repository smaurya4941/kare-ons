<?php

namespace App\Models;

use App\Services\CacheService;
use App\Support\HasIndexableScope;
use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasIndexableScope;
    use LogsActivity;

    /** Long-form field excluded from the audit diff. */
    protected array $activityLogIgnore = ['content', 'meta_description', 'seo_description'];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_indexable' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saved(fn () => CacheService::flushPages());
        static::deleted(fn () => CacheService::flushPages());
    }

    // scopeIndexable() is provided by App\Support\HasIndexableScope.
}
