@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-500">Manage discount coupons for your customers</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Create Coupon
    </a>
</div>

{{-- Search --}}
<form action="{{ route('admin.coupons.index') }}" method="GET" class="mb-6">
    <div class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 flex-1 max-w-xs">
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.coupons.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Clear</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Code</th>
                <th class="px-6 py-4 font-medium">Type</th>
                <th class="px-6 py-4 font-medium">Value</th>
                <th class="px-6 py-4 font-medium">Min. Order</th>
                <th class="px-6 py-4 font-medium">Used / Limit</th>
                <th class="px-6 py-4 font-medium">Expiry</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($coupons as $coupon)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded text-sm">{{ $coupon->code }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $coupon->type }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                    @if($coupon->type === 'percentage')
                        {{ $coupon->value }}%
                    @else
                        ₹{{ number_format($coupon->value, 2) }}
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    @if($coupon->minimum_order_amount > 0)
                        ₹{{ number_format($coupon->minimum_order_amount, 0) }}
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $coupon->used_count }}
                    @if($coupon->usage_limit)
                        / {{ $coupon->usage_limit }}
                    @else
                        / ∞
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    @if($coupon->expires_at)
                        <span class="{{ $coupon->expires_at->isPast() ? 'text-red-600' : 'text-gray-700' }}">
                            {{ $coupon->expires_at->format('M d, Y') }}
                        </span>
                    @else
                        <span class="text-gray-400">No expiry</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($coupon->status)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                              onsubmit="return confirm('Delete coupon {{ $coupon->code }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-400 text-sm">
                    No coupons found. <a href="{{ route('admin.coupons.create') }}" class="text-indigo-600 hover:underline">Create one now.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($coupons->hasPages())
    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
@endif
@endsection
