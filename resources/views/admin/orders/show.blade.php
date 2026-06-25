@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Orders
    </a>
    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
          onsubmit="return confirm('Delete this order permanently?');">
        @csrf @method('DELETE')
        <button type="submit" class="text-sm text-red-500 hover:text-red-700 flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px]">delete</span> Delete Order
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left column: order items + addresses --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Order Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-800">Order Items</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->main_image)
                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-gray-400 w-full h-full flex items-center justify-center">image</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-400">SKU: {{ $item->sku }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                        <p class="text-sm font-bold text-gray-900">₹{{ number_format($item->total, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->subtotal ?? $order->grand_total, 2) }}</span>
                </div>
                @if(isset($order->discount_amount) && $order->discount_amount > 0)
                <div class="flex justify-between text-sm text-emerald-600">
                    <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                    <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                @if(isset($order->shipping_amount) && $order->shipping_amount > 0)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Shipping</span>
                    <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-200 pt-2">
                    <span>Grand Total</span>
                    <span>₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Shipping Address</h2>
            @if($order->address)
            <address class="not-italic text-sm text-gray-600 leading-relaxed">
                <strong class="text-gray-800">{{ $order->address->full_name }}</strong><br>
                {{ $order->address->address_line_1 }}<br>
                @if($order->address->address_line_2){{ $order->address->address_line_2 }}<br>@endif
                {{ $order->address->city }}, {{ $order->address->state }} – {{ $order->address->pincode }}<br>
                {{ $order->address->country }}<br>
                📞 {{ $order->address->phone }}
            </address>
            @else
            <p class="text-sm text-gray-400">No address recorded.</p>
            @endif
        </div>
    </div>

    {{-- Right column: status + summary --}}
    <div class="space-y-6">

        {{-- Update Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Update Status</h2>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <select name="order_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->order_status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Optional note...">{{ $order->notes }}</textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span> Save Changes
                </button>
            </form>
        </div>

        {{-- Order Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3 text-sm">
            <h2 class="font-semibold text-gray-800 mb-4">Order Info</h2>
            <div class="flex justify-between">
                <span class="text-gray-500">Order #</span>
                <span class="font-medium text-gray-800">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Placed on</span>
                <span class="font-medium text-gray-800">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Payment</span>
                <span class="font-medium text-gray-800">{{ strtoupper($order->payment_method ?? 'COD') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Payment Status</span>
                <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ ucfirst($order->payment_status ?? 'pending') }}
                </span>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-sm">
            <h2 class="font-semibold text-gray-800 mb-4">Customer</h2>
            @if($order->user)
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                    {{ substr($order->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $order->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.customers.show', $order->user->id) }}" class="text-indigo-600 hover:underline text-xs">View profile →</a>
            @else
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                    {{ substr($order->address?->full_name ?? 'G', 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $order->address?->full_name ?? 'Guest' }}</p>
                    <p class="text-gray-400 text-xs">Phone: {{ $order->address?->phone ?? 'N/A' }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] rounded uppercase font-bold tracking-wider">Guest Checkout</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
