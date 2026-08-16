<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(fn () => \App\Services\CacheService::flushTestimonials());
        static::deleted(fn () => \App\Services\CacheService::flushTestimonials());
    }
}
