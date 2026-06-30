@extends('admin.settings.layout')

@section('title', 'Social Links')

@section('settings_content')
<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Social Links</h3>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
        <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
        <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
        <input type="url" name="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
        <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
</div>
@endsection
