<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index($tab = 'general')
    {
        $settings = Setting::firstOrCreate([]);
        $validTabs = ['general', 'homepage', 'contact', 'social', 'footer', 'payment', 'shipping', 'seo', 'email', 'whatsapp', 'invoice'];
        
        if (!in_array($tab, $validTabs)) {
            abort(404);
        }

        return view('admin.settings.tabs.' . $tab, compact('settings', 'tab'));
    }

    public function update(Request $request, $tab)
    {
        $settings = Setting::first();
        if (!$settings) {
            $settings = new Setting();
        }

        $rules = [];
        
        if ($tab === 'general') {
            $rules = [
                'site_name' => 'nullable|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,webp,ico,svg|max:1024',
                'timezone' => 'nullable|string',
                'currency' => 'nullable|string',
                'language' => 'nullable|string',
            ];
        } elseif ($tab === 'contact') {
            $rules = [
                'site_email' => 'nullable|email|max:255',
                'site_phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
            ];
        } elseif ($tab === 'social') {
            $rules = [
                'facebook_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'linkedin_url' => 'nullable|url',
                'twitter_url' => 'nullable|url',
                'youtube_url' => 'nullable|url',
            ];
        } elseif ($tab === 'footer') {
            $rules = [
                'copyright_text' => 'nullable|string|max:255',
                'about_text' => 'nullable|string',
            ];
        } elseif ($tab === 'homepage') {
            $rules = [
                'home_hero_title' => 'nullable|string',
                'home_hero_subtitle' => 'nullable|string',
                'home_cta_text' => 'nullable|string|max:100',
                'home_cta_link' => 'nullable|string|max:255',
                'home_hero_bg' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            ];
        } elseif ($tab === 'payment') {
            $rules = [
                'razorpay_key' => 'nullable|string',
                'razorpay_secret' => 'nullable|string',
                'razorpay_webhook_secret' => 'nullable|string',
            ];
        } elseif ($tab === 'shipping') {
            $rules = [
                'shipping_charge' => 'nullable|numeric|min:0',
                'free_shipping_amount' => 'nullable|numeric|min:0',
            ];
        } elseif ($tab === 'seo') {
            $rules = [
                'seo_meta_title' => 'nullable|string|max:255',
                'seo_meta_description' => 'nullable|string',
                'seo_meta_keywords' => 'nullable|string',
            ];
        } elseif ($tab === 'email') {
            $rules = [
                'smtp_host' => 'nullable|string|max:255',
                'smtp_port' => 'nullable|string|max:10',
                'smtp_user' => 'nullable|string|max:255',
                'smtp_password' => 'nullable|string|max:255',
                'smtp_encryption' => 'nullable|string|max:50',
                'smtp_from_address' => 'nullable|email|max:255',
            ];
        } elseif ($tab === 'whatsapp') {
            $rules = [
                'whatsapp_api_key' => 'nullable|string',
                'whatsapp_number' => 'nullable|string|max:20',
            ];
        } elseif ($tab === 'invoice') {
            $rules = [
                'invoice_company_details' => 'nullable|string',
                'invoice_gst_number' => 'nullable|string|max:50',
                'invoice_prefix' => 'nullable|string|max:20',
            ];
        }

        $validated = $request->validate($rules);

        // Handle File Upload for Logo
        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle File Upload for Favicon
        if ($request->hasFile('favicon')) {
            if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('settings', 'public');
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
        Cache::forget('homepage_data');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
