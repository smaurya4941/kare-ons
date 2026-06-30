@extends('admin.settings.layout')

@section('title', 'Contact Details')

@section('settings_content')
<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Contact Details</h3>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
        <input type="email" name="site_email" value="{{ old('site_email', $settings->site_email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
        <input type="text" name="site_phone" value="{{ old('site_phone', $settings->site_phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Physical Address</label>
        <textarea name="address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $settings->address) }}</textarea>
    </div>
</div>
@endsection
