@extends('admin.settings.layout')

@section('title', 'WhatsApp API Settings')

@section('settings_content')
<div class="p-6 space-y-6">
    <div class="border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600">forum</span>
            WhatsApp API Integration
        </h3>
        <p class="text-sm text-gray-500">Configure WhatsApp API credentials for sending automated alerts.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp API Key</label>
        <input type="text" name="whatsapp_api_key" value="{{ old('whatsapp_api_key', $settings->whatsapp_api_key) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Business Number</label>
        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="e.g. 919876543210" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        <p class="text-xs text-gray-500 mt-1">Include country code without the '+' sign.</p>
    </div>
</div>
@endsection
