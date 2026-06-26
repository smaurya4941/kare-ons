@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.coupons.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Coupons
    </a>
</div>

<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-semibold text-gray-800 text-lg mb-6 pb-4 border-b border-gray-100">New Coupon</h2>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <ul class="text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required
                       placeholder="e.g. WELCOME20"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase font-mono"
                       oninput="this.value = this.value.toUpperCase()">
                <p class="text-xs text-gray-400 mt-1">Will be auto-uppercased. No spaces allowed.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="flat" {{ old('type') === 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                <input type="number" name="value" value="{{ old('value') }}" required min="0" step="0.01"
                       placeholder="e.g. 20 for 20% or ₹20"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount (₹)</label>
                <input type="number" name="minimum_order_amount" value="{{ old('minimum_order_amount', 0) }}" min="0" step="1"
                       placeholder="0 = no minimum"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" step="1"
                       placeholder="Leave blank for unlimited"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <p class="text-xs text-gray-400 mt-1">Leave blank for no expiry</p>
            </div>

            <div class="flex items-center gap-3">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', '1') ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg text-sm transition">
                Create Coupon
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-6 py-2 rounded-lg text-sm transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
