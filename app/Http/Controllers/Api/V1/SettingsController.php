<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

/**
 * Publicly-safe subset of the `setting()`-backed Setting row. Never exposes
 * razorpay_secret / razorpay_webhook_secret / smtp_* or other server-only
 * credentials.
 */
class SettingsController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'site_name' => setting('site_name'),
                'site_email' => setting('site_email'),
                'site_phone' => setting('site_phone'),
                'logo' => image_url(setting('logo')),
                'favicon' => image_url(setting('favicon')),
                'address' => setting('address'),
                'about_text' => setting('about_text'),
                'copyright_text' => setting('copyright_text'),

                'social' => [
                    'facebook_url' => setting('facebook_url'),
                    'instagram_url' => setting('instagram_url'),
                    'youtube_url' => setting('youtube_url'),
                    'linkedin_url' => setting('linkedin_url'),
                    'twitter_url' => setting('twitter_url'),
                ],

                'home' => [
                    'hero_title' => setting('home_hero_title'),
                    'hero_subtitle' => setting('home_hero_subtitle'),
                    'hero_bg' => image_url(setting('home_hero_bg')),
                    'cta_text' => setting('home_cta_text'),
                    'cta_link' => setting('home_cta_link'),
                ],

                'shipping_charge' => (float) setting('shipping_charge', 0),
                'free_shipping_amount' => (float) setting('free_shipping_amount', 0),

                // Public Razorpay key only — never the secret.
                'razorpay_key' => setting('razorpay_key'),

                'currency' => setting('currency', 'INR'),
                'timezone' => setting('timezone', 'Asia/Kolkata'),

                'seo' => [
                    'meta_title' => setting('seo_meta_title'),
                    'meta_description' => setting('seo_meta_description'),
                    'meta_keywords' => setting('seo_meta_keywords'),
                ],

                'whatsapp_number' => setting('whatsapp_number'),
            ],
        ]);
    }
}
