@extends('admin.settings.layout')

@section('title', 'SMTP Email Settings')

@section('settings_content')
<div class="p-6 space-y-6">
    <div class="border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">mail</span>
            SMTP Settings
        </h3>
        <p class="text-sm text-gray-500">Configure your email provider to send outgoing emails.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
            <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host) }}" placeholder="e.g. smtp.gmail.com" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
            <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port) }}" placeholder="e.g. 587 or 465" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Username</label>
            <input type="text" name="smtp_user" value="{{ old('smtp_user', $settings->smtp_user) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Password</label>
            <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
            <select name="smtp_encryption" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="tls" {{ old('smtp_encryption', $settings->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                <option value="ssl" {{ old('smtp_encryption', $settings->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                <option value="" {{ old('smtp_encryption', $settings->smtp_encryption) == '' ? 'selected' : '' }}>None</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
            <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings->smtp_from_address) }}" placeholder="e.g. noreply@example.com" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>
    </div>
</div>
@endsection
