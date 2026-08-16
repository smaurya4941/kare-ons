<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushBrands());
        static::deleted(fn () => \App\Services\CacheService::flushBrands());
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
