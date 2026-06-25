@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or customer..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 w-64">
        </div>
        <select name="status" class="border border-gray-300 rounded-lg text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Statuses</option>
            <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>Pending</option>
            <option value="confirmed"  {{ request('status') == 'confirmed'  ? 'selected' : '' }}>Confirmed</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped"    {{ request('status') == 'shipped'    ? 'selected' : '' }}>Shipped</option>
            <option value="delivered"  {{ request('status') == 'delivered'  ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled"  {{ request('status') == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Order</th>
                    <th class="px-6 py-4 font-medium">Customer</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium">Items</th>
                    <th class="px-6 py-4 font-medium">Total</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">#{{ $order->order_number }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-800">{{ $order->user->name ?? $order->address?->full_name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email ?? $order->address?->phone ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->items->count() }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">₹{{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'pending'    => 'bg-amber-100 text-amber-800',
                                'confirmed'  => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-indigo-100 text-indigo-800',
                                'shipped'    => 'bg-purple-100 text-purple-800',
                                'delivered'  => 'bg-emerald-100 text-emerald-800',
                                'cancelled'  => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
