<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary)
    {
    }

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
                'home_ingredient_spotlight_title' => 'nullable|string',
                'home_ingredient_spotlight_ingredients' => 'nullable|string',
                'home_ingredient_spotlight_bg' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'home_expert_name' => 'nullable|string|max:100',
                'home_expert_designation' => 'nullable|string|max:100',
                'home_expert_description' => 'nullable|string|max:255',
                'home_expert_quote' => 'nullable|string',
                'home_expert_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
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
            if ($settings->logo) {
                $this->cloudinary->destroy($settings->logo);
            }
            $validated['logo'] = $this->cloudinary->upload($request->file('logo'), 'settings');
        }

        // Handle File Upload for Favicon
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                $this->cloudinary->destroy($settings->favicon);
            }
            $validated['favicon'] = $this->cloudinary->upload($request->file('favicon'), 'settings');
        }

        // Handle File Upload for Home Hero BG
        if ($request->hasFile('home_hero_bg')) {
            if ($settings->home_hero_bg) {
                $this->cloudinary->destroy($settings->home_hero_bg);
            }
            $validated['home_hero_bg'] = $this->cloudinary->upload($request->file('home_hero_bg'), 'settings');
        }

        // Handle File Upload for Ingredient Spotlight BG
        if ($request->hasFile('home_ingredient_spotlight_bg')) {
            if ($settings->home_ingredient_spotlight_bg) {
                $this->cloudinary->destroy($settings->home_ingredient_spotlight_bg);
            }
            $validated['home_ingredient_spotlight_bg'] = $this->cloudinary->upload($request->file('home_ingredient_spotlight_bg'), 'settings');
        }

        // Handle File Upload for Expert Image
        if ($request->hasFile('home_expert_image')) {
            if ($settings->home_expert_image) {
                $this->cloudinary->destroy($settings->home_expert_image);
            }
            $validated['home_expert_image'] = $this->cloudinary->upload($request->file('home_expert_image'), 'settings');
        }

        $settings->fill($validated);
        $settings->save();
        // Cache invalidation (global_settings + homepage_data) happens in
        // Setting::booted() so every write path — here, tinker, seeders — stays covered.

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
