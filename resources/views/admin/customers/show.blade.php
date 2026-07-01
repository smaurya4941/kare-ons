@extends('admin.layouts.app')

@section('title', 'Customer: ' . $customer->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Customers
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Customer Profile & Settings --}}
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 text-center relative">
            @if(!$customer->status)
            <span class="absolute top-4 right-4 bg-red-100 text-red-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Blocked</span>
            @endif
            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-600 text-xl mx-auto mb-2">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <h2 class="text-sm font-bold text-gray-800">{{ $customer->name }}</h2>
            <p class="text-[11px] text-gray-400 font-mono mb-1">{{ $customer->email }}</p>
            <p class="text-[9px] text-gray-400 uppercase tracking-wider">Member since {{ $customer->created_at->format('M Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 space-y-3 text-[11px]">
            <h3 class="font-semibold text-gray-800 text-sm mb-2">Summary</h3>
            <div class="flex justify-between">
                <span class="text-gray-500">Total Orders</span>
                <span class="font-semibold text-gray-800">{{ $customer->orders->count() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Total Spent</span>
                <span class="font-semibold text-gray-800">₹{{ number_format($customer->orders->sum('grand_total'), 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Reward Points</span>
                <span class="font-semibold text-amber-600 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">stars</span> {{ $customer->reward_points }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Wallet Balance</span>
                <span class="font-semibold text-emerald-600">₹{{ number_format($customer->wallet_balance, 2) }}</span>
            </div>
        </div>

        {{-- Account Settings Form --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 text-sm mb-4">Account Administration</h3>
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Reward Points</label>
                        <input type="number" name="reward_points" value="{{ old('reward_points', $customer->reward_points) }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500" min="0">
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Wallet (₹)</label>
                        <input type="number" step="0.01" name="wallet_balance" value="{{ old('wallet_balance', $customer->wallet_balance) }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500" min="0">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Account Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1" {{ $customer->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$customer->status ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wider mb-1">Internal Notes</label>
                    <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="Notes about this customer...">{{ old('notes', $customer->notes) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-md transition text-[11px] flex justify-center items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">save</span> Update Account
                </button>
            </form>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Addresses & Wishlist Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Saved Addresses --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800 text-sm">Addresses</h2>
                    <span class="text-[9px] text-gray-500 uppercase tracking-wider font-bold">{{ $customer->addresses->count() }} Saved</span>
                </div>
                <div class="p-5 max-h-64 overflow-y-auto">
                    @forelse($customer->addresses as $address)
                    <address class="not-italic text-gray-600 leading-relaxed text-[11px] mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-0 border-gray-100">
                        <strong class="text-gray-800">{{ $address->full_name ?? $address->name }}</strong>
                        @if($address->is_default) <span class="bg-indigo-100 text-indigo-700 text-[9px] px-1.5 py-0.5 rounded ml-1">Default</span> @endif<br>
                        {{ $address->address_line_1 }}<br>
                        @if($address->address_line_2) {{ $address->address_line_2 }}<br> @endif
                        {{ $address->city }}, {{ $address->state }} – {{ $address->postal_code ?? $address->pincode }}<br>
                        📞 {{ $address->phone }}
                    </address>
                    @empty
                    <p class="text-[11px] text-gray-500 italic">No saved addresses.</p>
                    @endforelse
                </div>
            </div>

            {{-- Wishlist --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800 text-sm">Wishlist</h2>
                    <span class="text-[9px] text-gray-500 uppercase tracking-wider font-bold">{{ $customer->wishlists->count() }} Items</span>
                </div>
                <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                    @forelse($customer->wishlists as $wishlist)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                            @if($wishlist->product && $wishlist->product->main_image)
                                <img src="{{ asset('storage/' . $wishlist->product->main_image) }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-gray-400 w-full h-full flex items-center justify-center text-[16px]">image</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] font-semibold text-gray-800 line-clamp-1">{{ $wishlist->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-[9px] text-gray-500">₹{{ number_format($wishlist->product->price ?? 0, 2) }}</p>
                        </div>
                        <a href="{{ $wishlist->product ? route('admin.products.edit', $wishlist->product_id) : '#' }}" class="text-indigo-600 hover:text-indigo-900"><span class="material-symbols-outlined text-[16px]">open_in_new</span></a>
                    </div>
                    @empty
                    <div class="px-5 py-4 text-[11px] text-gray-500 italic">Wishlist is empty.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Order History --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-800 text-sm">Order History</h2>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($customer->orders->sortByDesc('created_at') as $order)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                    <div>
                        <p class="text-[11px] font-semibold text-gray-800">#{{ $order->order_number }}</p>
                        <p class="text-[9px] text-gray-400 font-mono">{{ $order->created_at->format('M d, Y h:i A') }} · {{ $order->items->count() }} item(s)</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] font-bold text-gray-800">₹{{ number_format($order->grand_total, 2) }}</span>
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
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider {{ $color }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded text-[11px] font-medium transition hover:bg-indigo-100">View</a>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-500 text-[11px]">No orders yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
