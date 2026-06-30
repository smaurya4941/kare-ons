@extends('admin.settings.layout')

@section('title', 'Global SEO Settings')

@section('settings_content')
<div class="p-6 space-y-6">
    <div class="border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">travel_explore</span>
            Global SEO Settings
        </h3>
        <p class="text-sm text-gray-500">Manage your website's default meta tags for search engines.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Global Meta Title</label>
        <input type="text" name="seo_meta_title" value="{{ old('seo_meta_title', $settings->seo_meta_title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        <p class="text-xs text-gray-500 mt-1">Recommended length: 50-60 characters.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Global Meta Description</label>
        <textarea name="seo_meta_description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('seo_meta_description', $settings->seo_meta_description) }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Recommended length: 150-160 characters.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
        <input type="text" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $settings->seo_meta_keywords) }}" placeholder="e.g. ecommerce, products, shop" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        <p class="text-xs text-gray-500 mt-1">Comma-separated list of keywords.</p>
    </div>
</div>
@endsection
