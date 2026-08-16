<x-customer-layout>
    <x-slot name="title">My Orders</x-slot>

    <div class="flex-1">
                    @if(session('success'))
                        <div class="mb-6 bg-secondary-container text-on-secondary-container p-4 rounded-lg flex items-center gap-3">
                            <span class="material-symbols-outlined">check_circle</span>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-10 text-center">
                            <div class="w-16 h-16 bg-herbal-light mx-auto rounded-full flex items-center justify-center mb-5">
                                <span class="material-symbols-outlined text-herbal-accent text-3xl">inbox</span>
                            </div>
                            <h3 class="text-xl font-bold text-on-surface mb-2">No orders yet</h3>
                            <p class="text-sm text-on-surface-variant mb-6">You haven't placed any orders yet. Explore our Ayurvedic range!</p>
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:bg-primary/90 transition">
                                <span class="material-symbols-outlined text-[18px]">storefront</span>
                                Browse Products
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
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
                                <div class="bg-surface rounded-lg border border-outline-variant shadow-sm overflow-hidden mb-4">
                                    {{-- Order Header --}}
                                    <div class="px-4 py-3 border-b border-outline-variant bg-surface-container-lowest flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="font-bold text-on-surface text-base tracking-tight">#{{ $order->order_number }}</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $color }}">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-on-surface-variant">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="text-right">
                                                <p class="font-bold text-on-surface text-base">₹{{ number_format($order->grand_total, 2) }}</p>
                                                <p class="text-[11px] text-on-surface-variant">Total</p>
                                            </div>
                                            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center justify-center border border-outline-variant hover:bg-surface-container transition-colors rounded-md px-3 py-1.5 text-xs font-medium text-on-surface">
                                                View Details
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Order Items --}}
                                    <div class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @php $firstItem = $order->items->first(); @endphp
                                            <div class="w-10 h-10 bg-surface-container rounded-md border border-outline-variant overflow-hidden flex-shrink-0">
                                                @if($firstItem && $firstItem->product && $firstItem->product->main_image)
                                                    <img src="{{ image_url($firstItem->product->main_image) }}" alt="{{ $firstItem->product_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-outline text-[16px]">image</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-on-surface line-clamp-1">
                                                    {{ $firstItem ? $firstItem->product_name : 'Unknown Product' }}
                                                </p>
                                                <p class="text-xs text-on-surface-variant">
                                                    @if($order->items->count() > 1)
                                                        + {{ $order->items->count() - 1 }} other item(s)
                                                    @else
                                                        Qty: {{ $firstItem ? $firstItem->quantity : 0 }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Payment & Delivery Info --}}
                                        <div class="mt-3 pt-3 border-t border-outline-variant flex flex-wrap gap-4 text-xs text-on-surface-variant">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[14px]">payments</span>
                                                {{ strtoupper($order->payment_method) }}
                                                <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                                    ({{ ucfirst($order->payment_status) }})
                                                </span>
                                            </div>
                                            @if($order->address)
                                                <div class="flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                                    {{ $order->address->city }}, {{ $order->address->state }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    @if($order->order_status === 'delivered')
                                        <div class="px-4 pb-3">
                                            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:underline">
                                                <span class="material-symbols-outlined text-[14px]">repeat</span>
                                                Buy Again
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if($orders->hasPages())
                            <div class="mt-8">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
    </div>
</x-customer-layout>
