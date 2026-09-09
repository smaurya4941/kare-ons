<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storefront mode
    |--------------------------------------------------------------------------
    |
    | 'blade'    — this Laravel app serves the customer storefront with Blade
    |              (the public + authenticated-customer routes in
    |              routes/web.php). This is the DEFAULT and what production
    |              runs today.
    |
    | 'headless' — the storefront is served by the standalone Next.js app
    |              (kare-ons-web) against the /api/v1 API. The Blade storefront
    |              routes are disabled and 302 to `storefront.url`; only
    |              /admin, the API, Breeze session auth and the Razorpay
    |              webhook stay on Laravel.
    |
    | Flip to 'headless' ONLY once the Next.js app is deployed and
    | STOREFRONT_URL (or FRONTEND_URL) points at it.
    |
    */

    'mode' => env('STOREFRONT_MODE', 'blade'),

    /*
    | Absolute base URL of the headless storefront (no trailing slash).
    | Falls back to FRONTEND_URL, then APP_URL. Only used when mode = headless.
    */

    'url' => rtrim(
        env('STOREFRONT_URL', env('FRONTEND_URL', env('APP_URL', 'http://localhost'))),
        '/'
    ),

];
