@extends('admin.layouts.app')

@section('title', 'Customer: ' . $customer->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Customers
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Customer Profile Card --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-600 text-2xl mx-auto mb-3">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <h2 class="text-lg font-bold text-gray-800">{{ $customer->name }}</h2>
            <p class="text-sm text-gray-400 mb-1">{{ $customer->email }}</p>
            <p class="text-xs text-gray-400">Member since {{ $customer->created_at->format('M Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4 text-sm">
            <h3 class="font-semibold text-gray-800">Summary</h3>
            <div class="flex justify-between">
                <span class="text-gray-500">Total Orders</span>
                <span class="font-semibold text-gray-800">{{ $customer->orders->count() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Total Spent</span>
                <span class="font-semibold text-gray-800">₹{{ number_format($customer->orders->sum('grand_total'), 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Pending Orders</span>
                <span class="font-semibold text-amber-600">{{ $customer->orders->where('order_status', 'pending')->count() }}</span>
            </div>
        </div>

        @if($customer->addresses->count())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-sm">
            <h3 class="font-semibold text-gray-800 mb-3">Saved Addresses</h3>
            @foreach($customer->addresses as $address)
            <address class="not-italic text-gray-600 leading-relaxed text-xs mb-3 last:mb-0 pb-3 last:pb-0 border-b last:border-0 border-gray-100">
                <strong>{{ $address->name }}</strong><br>
                {{ $address->address_line_1 }}, {{ $address->city }}, {{ $address->state }} – {{ $address->pincode }}<br>
                📞 {{ $address->phone }}
            </address>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Order History --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-800">Order History</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($customer->orders->sortByDesc('created_at') as $order)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">#{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y h:i A') }} · {{ $order->items->count() }} item(s)</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800">₹{{ number_format($order->grand_total, 2) }}</span>
                        @php
                            $statusColors = [
                                'pending'    => 'bg-amber-100 text-amber-800',
                                'confirmed'  => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-indigo-100 text-indigo-800',
                                'shipped'    => 'bg-purple-100 text-purple-800',
                                'delivered'  => 'bg-emerald-100 text-emerald-800',
                                'cancelled'  => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:underline text-xs font-medium">View</a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-gray-500 text-sm">No orders yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
