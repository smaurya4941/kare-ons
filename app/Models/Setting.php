<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = ['id'];

    /** Only the update event is meaningful for the single settings row. */
    protected array $activityLogEvents = ['updated'];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushSettings());
        static::deleted(fn () => \App\Services\CacheService::flushSettings());
    }

    public function activityLabel(): string
    {
        return 'store settings';
    }
}