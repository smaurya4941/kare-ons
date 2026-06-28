<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::firstOrCreate([]);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::first();
        if (!$settings) {
            $settings = new Setting();
        }

        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'copyright_text' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'home_hero_title' => 'nullable|string',
            'home_hero_subtitle' => 'nullable|string',
            'home_cta_text' => 'nullable|string|max:100',
            'home_cta_link' => 'nullable|string|max:255',
            'home_hero_bg' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle File Upload for Logo
        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle File Upload for Home Hero BG
        if ($request->hasFile('home_hero_bg')) {
            if ($settings->home_hero_bg && Storage::disk('public')->exists($settings->home_hero_bg)) {
                Storage::disk('public')->delete($settings->home_hero_bg);
            }
            $validated['home_hero_bg'] = $request->file('home_hero_bg')->store('settings', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        // Clear the cached settings so the site updates instantly
        Cache::forget('global_settings');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
