<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        // Cache the settings row forever. Setting::booted() clears this on any write.
        $settings = Cache::rememberForever(\App\Support\Cache\CacheKeys::GLOBAL_SETTINGS, function () {
            // Get the first settings row, or create an empty one if it doesn't exist
            return Setting::firstOrCreate([], [
                'site_name' => 'Kare ONS Herbals',
                'site_email' => 'info@kareons.com',
                'site_phone' => '+123456789',
                'copyright_text' => '© ' . date('Y') . ' Kare ONS Herbals. All rights reserved.',
                'about_text' => 'Setting the global benchmark for scientific Ayurveda and botanical clinical excellence since 1999.',
                'home_hero_title' => "Nature's Wisdom, <br/><span class=\"text-secondary\">Refined by Science.</span>",
                'home_hero_subtitle' => 'Pioneering clinical-grade Ayurvedic medicine through rigorous scientific validation. Our state-of-the-art manufacturing facilities deliver holistic wellness solutions that honor ancient traditions while meeting modern pharmaceutical standards.',
                'home_cta_text' => 'Start Your Inquiry',
                'home_cta_link' => '/shop',
                'home_ingredient_spotlight_title' => 'The Essence of Vedic Wisdom',
                'home_ingredient_spotlight_ingredients' => 'Neem,Tulsi,Ashwagandha,Amla',
            ]);
        });

        // If the specific column is requested, return it or the default
        if ($settings && isset($settings->{$key}) && $settings->{$key} !== '') {
            return $settings->{$key};
        }

        return $default;
    }
}

if (!function_exists('image_url')) {
    /**
     * Build a display URL for a stored image field.
     *
     * New uploads go through Cloudinary and are stored as full secure URLs,
     * so those are returned as-is. Records created before the Cloudinary
     * migration still hold a relative "public" disk path, so those fall back
     * to the local storage URL.
     */
    function image_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}

if (!function_exists('toast_payload')) {
    /**
     * Build a structured toast payload for flashing to the session.
     *
     * The customer layout reads `session('toast')` and renders a titled toast
     * (optionally with an action link) via the global showToast() helper.
     *
     * Usage:
     *   return redirect()->route('orders.index')
     *       ->with('toast', toast_payload('Your order was placed.', 'success', 'Order Placed', [
     *           'label' => 'View Orders', 'url' => route('orders.index'),
     *       ]));
     *
     * @param  string       $message  Body text of the toast.
     * @param  string       $type     success | error | info
     * @param  string|null  $title    Optional bold heading (e.g. "Added to Cart").
     * @param  array|null   $action   Optional link: ['label' => ..., 'url' => ...].
     */
    function toast_payload(string $message, string $type = 'success', ?string $title = null, ?array $action = null): array
    {
        $payload = [
            'message' => $message,
            'type'    => $type,
        ];

        if ($title !== null) {
            $payload['title'] = $title;
        }

        if ($action !== null && !empty($action['label']) && !empty($action['url'])) {
            $payload['action'] = [
                'label' => $action['label'],
                'url'   => $action['url'],
            ];
        }

        return $payload;
    }
}
