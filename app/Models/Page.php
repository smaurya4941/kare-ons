<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use LogsActivity;

    /** Long-form field excluded from the audit diff. */
    protected array $activityLogIgnore = ['content', 'meta_description', 'seo_description'];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status'       => 'boolean',
            'is_indexable' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushPages());
        static::deleted(fn () => \App\Services\CacheService::flushPages());
    }

    /**
     * Active pages that are allowed to be indexed by search engines.
     */
    public function scopeIndexable($query)
    {
        return $query->where('status', true)->where('is_indexable', true);
    }
}
