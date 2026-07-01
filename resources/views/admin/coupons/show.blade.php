@extends('admin.layouts.app')

@section('title', 'Coupon Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.coupons.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Coupons
    </a>
    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
        <span class="material-symbols-outlined text-[18px]">edit</span> Edit
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded text-lg">{{ $coupon->code }}</span>
            @if($coupon->status)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
            @endif
        </div>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-800 capitalize">{{ $coupon->type }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Value</dt><dd class="font-medium text-gray-800">{{ $coupon->type === 'percentage' ? $coupon->value . '%' : '₹' . number_format($coupon->value, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Min. Order</dt><dd class="font-medium text-gray-800">{{ $coupon->minimum_order_amount > 0 ? '₹' . number_format($coupon->minimum_order_amount, 0) : '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Used / Limit</dt><dd class="font-medium text-gray-800">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Starts</dt><dd class="font-medium text-gray-800">{{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Expires</dt><dd class="font-medium text-gray-800">{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'No expiry' }}</dd></div>
        </dl>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Usage History ({{ $coupon->usages->count() }})</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3 font-medium">Customer</th>
                    <th class="px-6 py-3 font-medium">Order</th>
                    <th class="px-6 py-3 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($coupon->usages as $usage)
                <tr>
                    <td class="px-6 py-3 text-sm text-gray-700">{{ $usage->user->name ?? 'Guest' }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">
                        @if($usage->order)
                            <a href="{{ route('admin.orders.show', $usage->order->id) }}" class="text-indigo-600 hover:underline">{{ $usage->order->order_number }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500">{{ $usage->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400 text-sm">This coupon has not been used yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
