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
        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('homepage_data');
        });

        static::deleted(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('homepage_data');
        });
    }
}
