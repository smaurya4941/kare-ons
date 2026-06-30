@extends('admin.settings.layout')

@section('title', 'Shipping Settings')

@section('settings_content')
<div class="p-6 space-y-6">
    <div class="border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">local_shipping</span>
            Shipping Settings
        </h3>
        <p class="text-sm text-gray-500">Configure your shipping costs and free shipping thresholds.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Flat Shipping Charge</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">₹</span>
                <input type="number" step="0.01" name="shipping_charge" value="{{ old('shipping_charge', $settings->shipping_charge ?? 50) }}" class="w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <p class="text-xs text-gray-500 mt-1">Default shipping cost applied to orders.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Free Shipping Minimum Amount</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">₹</span>
                <input type="number" step="0.01" name="free_shipping_amount" value="{{ old('free_shipping_amount', $settings->free_shipping_amount ?? 500) }}" class="w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <p class="text-xs text-gray-500 mt-1">Orders above this amount get free shipping.</p>
        </div>
    </div>
</div>
@endsection
