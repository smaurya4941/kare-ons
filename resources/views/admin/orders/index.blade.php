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
        <select name="status" class="border border-gray-200 rounded-md text-[11px] px-3 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
            <option value="">All Statuses</option>
            <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>Pending</option>
            <option value="confirmed"  {{ request('status') == 'confirmed'  ? 'selected' : '' }}>Confirmed</option>
            <option value="packed"     {{ request('status') == 'packed'     ? 'selected' : '' }}>Packed</option>
            <option value="shipped"    {{ request('status') == 'shipped'    ? 'selected' : '' }}>Shipped</option>
            <option value="delivered"  {{ request('status') == 'delivered'  ? 'selected' : '' }}>Delivered</option>
            <option value="returned"   {{ request('status') == 'returned'   ? 'selected' : '' }}>Returned</option>
            <option value="cancelled"  {{ request('status') == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Order</th>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Date</th>
                    <th class="px-4 py-3 font-medium">Items</th>
                    <th class="px-4 py-3 font-medium">Total</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-[11px] font-semibold text-gray-800">#{{ $order->order_number }}</td>
                    <td class="px-4 py-3">
                        <p class="text-[11px] font-medium text-gray-800">{{ $order->user->name ?? $order->address?->full_name ?? 'Guest' }}</p>
                        <p class="text-[9px] text-gray-400 font-mono">{{ $order->user->email ?? $order->address?->phone ?? 'N/A' }}</p>
                    </td>
                    <td class="px-4 py-3 text-[11px] text-gray-500">{{ $order->created_at->format('M d, Y') }}<br>
                        <span class="text-[9px] text-gray-400 font-mono">{{ $order->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="px-4 py-3 text-[11px] text-gray-600">{{ $order->items->count() }}</td>
                    <td class="px-4 py-3 text-[11px] font-bold text-gray-800">₹{{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'pending'    => 'bg-amber-100 text-amber-800',
                                'confirmed'  => 'bg-blue-100 text-blue-800',
                                'packed'     => 'bg-indigo-100 text-indigo-800',
                                'shipped'    => 'bg-purple-100 text-purple-800',
                                'delivered'  => 'bg-emerald-100 text-emerald-800',
                                'returned'   => 'bg-orange-100 text-orange-800',
                                'cancelled'  => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider {{ $color }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 text-[11px] font-medium bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100 transition">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 text-[11px]">No orders found.</td>
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
