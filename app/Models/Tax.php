<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushTaxes());
        static::deleted(fn () => \App\Services\CacheService::flushTaxes());
    }
}
