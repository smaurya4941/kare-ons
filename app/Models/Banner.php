<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushBanners());
        static::deleted(fn () => \App\Services\CacheService::flushBanners());
    }
}
