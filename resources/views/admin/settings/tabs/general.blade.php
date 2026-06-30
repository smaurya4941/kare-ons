@extends('admin.settings.layout')

@section('title', 'General Settings')

@section('settings_content')
<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">General Settings</h3>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
        <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Site Logo</label>
        @if($settings->logo)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Current Logo" class="h-12 bg-gray-100 p-2 rounded">
            </div>
        @endif
        <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Browser Favicon</label>
        @if($settings->favicon)
            <div class="mb-2">
                <img src="{{ asset('storage/'.$settings->favicon) }}" alt="Favicon" class="h-8 w-8 object-contain border rounded p-1 bg-gray-50">
            </div>
        @endif
        <input type="file" name="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <p class="mt-1 text-xs text-gray-500">Recommended size: 32x32px or 16x16px (.png, .ico)</p>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
        <select name="timezone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="Asia/Kolkata" {{ old('timezone', $settings->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
            <option value="UTC" {{ old('timezone', $settings->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
            <option value="America/New_York" {{ old('timezone', $settings->timezone) == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
            <input type="text" name="currency" value="{{ old('currency', $settings->currency) }}" placeholder="e.g. INR or USD" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
            <input type="text" name="language" value="{{ old('language', $settings->language) }}" placeholder="e.g. en" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
    </div>
</div>
@endsection
