<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reclaim stock from Razorpay orders abandoned before the payment completed.
Schedule::command('payments:expire-stale')
    ->everyFifteenMinutes()
    ->withoutOverlapping();
