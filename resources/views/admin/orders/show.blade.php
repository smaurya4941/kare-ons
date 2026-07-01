@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Orders
    </a>
    <div class="flex items-center gap-3">
        <a href="{{ route('orders.invoice', $order->id) }}" target="_blank" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">receipt_long</span> Invoice
        </a>
        <a href="{{ route('orders.packing_slip', $order->id) }}" target="_blank" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">inventory_2</span> Packing Slip
        </a>
        <a href="{{ route('orders.shipping_label', $order->id) }}" target="_blank" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">local_shipping</span> Label
        </a>
        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
              onsubmit="return confirm('Delete this order permanently?');">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-500 hover:text-red-700 flex items-center gap-1 px-2">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left column: order items + addresses --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Order Items --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 text-sm">Order Items</h2>
                <span class="text-[11px] text-gray-500">{{ $order->items->count() }} items</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="px-5 py-3 flex items-center gap-4">
                    <div class="w-12 h-12 rounded bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->main_image)
                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-gray-400 w-full h-full flex items-center justify-center text-[20px]">image</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-semibold text-gray-800">{{ $item->product_name }}</p>
                        <p class="text-[9px] text-gray-400 font-mono">SKU: {{ $item->sku }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-medium text-gray-800">₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                        <p class="text-[11px] font-bold text-gray-900 mt-0.5">₹{{ number_format($item->total, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 space-y-1.5">
                <div class="flex justify-between text-[11px] text-gray-600">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->subtotal ?? $order->grand_total, 2) }}</span>
                </div>
                @if(isset($order->discount_amount) && $order->discount_amount > 0)
                <div class="flex justify-between text-[11px] text-emerald-600">
                    <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                    <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                @if(isset($order->shipping_charge) && $order->shipping_charge > 0)
                <div class="flex justify-between text-[11px] text-gray-600">
                    <span>Shipping</span>
                    <span>₹{{ number_format($order->shipping_charge, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 text-[13px] border-t border-gray-200 pt-1.5 mt-1.5">
                    <span>Grand Total</span>
                    <span>₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Shipping Address</h2>
            @if($order->address)
            <address class="not-italic text-sm text-gray-600 leading-relaxed">
                <strong class="text-gray-800">{{ $order->address->full_name }}</strong><br>
                {{ $order->address->address_line_1 }}<br>
                @if($order->address->address_line_2){{ $order->address->address_line_2 }}<br>@endif
                {{ $order->address->city }}, {{ $order->address->state }} – {{ $order->address->postal_code }}<br>
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-800 text-sm mb-4">Update Status</h2>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Order Status</label>
                        <select name="order_status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['pending','confirmed','packed','shipped','delivered','returned','cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->order_status === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Refund Status</label>
                        <select name="refund_status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['none','pending','partial','refunded'] as $status)
                            <option value="{{ $status }}" {{ ($order->refund_status ?? 'none') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Internal Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Optional note..."></textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md transition text-[11px] flex justify-center items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">save</span> Save Changes
                </button>
            </form>
        </div>

        {{-- Order Info --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 space-y-2 text-[11px]">
            <h2 class="font-semibold text-gray-800 text-sm mb-3">Order Info</h2>
            <div class="flex justify-between">
                <span class="text-gray-500">Order #</span>
                <span class="font-semibold text-gray-800">{{ $order->order_number }}</span>
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
            <div class="flex justify-between">
                <span class="text-gray-500">Refund Status</span>
                <span class="font-medium {{ ($order->refund_status ?? 'none') === 'none' ? 'text-gray-400' : 'text-blue-600' }}">
                    {{ ucfirst($order->refund_status ?? 'none') }}
                </span>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 text-[11px]">
            <h2 class="font-semibold text-gray-800 text-sm mb-3">Customer</h2>
            @if($order->user)
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-xs">
                    {{ substr($order->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                    <p class="text-gray-400 font-mono">{{ $order->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.customers.show', $order->user->id) }}" class="text-indigo-600 hover:underline">View profile →</a>
            @else
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-xs">
                    {{ substr($order->address?->full_name ?? 'G', 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $order->address?->full_name ?? 'Guest' }}</p>
                    <p class="text-gray-400 font-mono">Phone: {{ $order->address?->phone ?? 'N/A' }}</p>
                    <span class="inline-block mt-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 text-[9px] rounded uppercase font-bold tracking-wider">Guest Checkout</span>
                </div>
            </div>
            @endif
        </div>

        {{-- Order Timeline --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-800 text-sm mb-4">Order Timeline</h2>
            <div class="relative border-l border-gray-200 ml-2 space-y-4 pb-2">
                @forelse($order->timelines as $timeline)
                <div class="pl-4 relative">
                    <div class="absolute w-2.5 h-2.5 bg-indigo-500 rounded-full -left-[5px] top-1.5 ring-4 ring-white"></div>
                    <div class="text-[11px]">
                        <p class="font-semibold text-gray-800 capitalize">{{ $timeline->status }}</p>
                        <p class="text-gray-400 text-[9px] font-mono mb-1">{{ $timeline->created_at->format('M d, Y h:i A') }}</p>
                        @if($timeline->notes)
                        <p class="text-gray-600 bg-gray-50 p-2 rounded mt-1 border border-gray-100">{{ $timeline->notes }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="pl-4 text-[11px] text-gray-500 italic">No timeline events recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
