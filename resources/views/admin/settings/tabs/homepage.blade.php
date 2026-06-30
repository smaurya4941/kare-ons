@extends('admin.settings.layout')

@section('title', 'Homepage Setup')

@section('settings_content')
<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Homepage Hero Section</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hero Title</label>
            <input type="text" name="home_hero_title" value="{{ old('home_hero_title', $settings->home_hero_title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-xs text-gray-500 mt-1">Note: You can use HTML like &lt;br&gt; or &lt;span class="text-secondary"&gt; for styling.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hero Background Image</label>
            @if($settings->home_hero_bg)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $settings->home_hero_bg) }}" alt="Current Hero BG" class="h-20 object-cover bg-gray-100 p-1 rounded">
                </div>
            @endif
            <input type="file" name="home_hero_bg" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Hero Subtitle</label>
            <textarea name="home_hero_subtitle" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('home_hero_subtitle', $settings->home_hero_subtitle) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Call-to-Action Text</label>
            <input type="text" name="home_cta_text" value="{{ old('home_cta_text', $settings->home_cta_text) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Call-to-Action Link</label>
            <input type="text" name="home_cta_link" value="{{ old('home_cta_link', $settings->home_cta_link) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., /shop">
        </div>
    </div>
</div>
@endsection
