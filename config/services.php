<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Trusted Frontend
    |--------------------------------------------------------------------------
    |
    | Shared secret the Next.js storefront (kare-ons-web) sends on every API
    | request as the `X-Frontend-Key` header. All storefront traffic reaches
    | this API from a handful of Vercel server IPs, so it cannot be rate
    | limited per-IP like a public client — a request carrying this key is
    | treated as first-party and gets a much higher throttle budget. Leave
    | unset to disable the check (every caller then shares the public limit).
    |
    */
    'frontend' => [
        'key' => env('FRONTEND_API_KEY'),
    ],

];
