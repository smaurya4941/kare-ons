@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
            <span class="material-symbols-outlined">inventory_2</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Products</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_products']) }}</h3>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
            <span class="material-symbols-outlined">shopping_cart</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Orders</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</h3>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
            <span class="material-symbols-outlined">payments</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
            <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($stats['total_revenue'], 2) }}</h3>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined">group</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Customers</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_customers']) }}</h3>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h2 class="font-semibold text-gray-800 text-lg">Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Order ID</th>
                    <th class="px-6 py-4 font-medium">Customer</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium">Total</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">#{{ $order->order_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">₹{{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($order->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                        @elseif($order->status === 'processing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Processing</span>
                        @elseif($order->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Completed</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
