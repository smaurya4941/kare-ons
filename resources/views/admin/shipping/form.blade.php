@extends('admin.layouts.app')

@section('title', $zone->exists ? 'Edit Shipping Zone' : 'Create Shipping Zone')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.shipping.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Shipping
    </a>
</div>

<form action="{{ $zone->exists ? route('admin.shipping.update', $zone->id) : route('admin.shipping.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    @csrf
    @if($zone->exists) @method('PUT') @endif

    <div class="space-y-5">
        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Zone Name *</label>
            <input type="text" name="name" value="{{ old('name', $zone->name) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Maharashtra, Rest of India">
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Coverage (Pincodes / States)</label>
            <textarea name="coverage" rows="3" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter comma-separated pincodes or states. Leave empty for 'Rest of the world / Default'">{{ old('coverage', $zone->coverage) }}</textarea>
            <p class="text-[10px] text-gray-500 mt-1">If this is your default fallback zone, you can leave this blank.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Base Shipping Charge (₹) *</label>
                <input type="number" step="0.01" name="base_charge" value="{{ old('base_charge', $zone->base_charge ?? 0) }}" required min="0" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Free Shipping Above (₹)</label>
                <input type="number" step="0.01" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $zone->free_shipping_threshold) }}" min="0" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="Leave blank for no free shipping">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">COD Surcharge (₹) *</label>
                <input type="number" step="0.01" name="cod_charge" value="{{ old('cod_charge', $zone->cod_charge ?? 0) }}" required min="0" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $zone->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                <span class="ml-2 text-[12px] font-medium text-gray-700">Active Zone</span>
            </label>
            
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_default" value="1" {{ old('is_default', $zone->is_default) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                <span class="ml-2 text-[12px] font-medium text-gray-700">Set as Default (Fallback) Zone</span>
            </label>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
            <a href="{{ route('admin.shipping.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium">Cancel</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                {{ $zone->exists ? 'Update Zone' : 'Create Zone' }}
            </button>
        </div>
    </div>
</form>
@endsection
