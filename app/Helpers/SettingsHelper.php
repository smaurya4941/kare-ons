<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        // Cache the settings row forever. Clear this cache when settings are updated.
        $settings = Cache::rememberForever('global_settings', function () {
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
            ]);
        });

        // If the specific column is requested, return it or the default
        if ($settings && isset($settings->{$key}) && $settings->{$key} !== '') {
            return $settings->{$key};
        }

        return $default;
    }
}
