<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-on-surface leading-tight">
            My Orders
        </h2>
    </x-slot>

    <div class="py-12 bg-surface-container-lowest min-h-screen">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Sidebar --}}
                <div class="w-full lg:w-64 flex-shrink-0">
                    <div class="bg-surface rounded-xl border border-outline-variant p-4 sticky top-24 shadow-sm">
                        <div class="flex items-center gap-4 p-4 mb-4 border-b border-outline-variant">
                            <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-on-surface line-clamp-1">{{ Auth::user()->name }}</h3>
                                <p class="text-xs text-secondary line-clamp-1">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <nav class="space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span> Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">person</span> Profile
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-secondary-container text-on-secondary-container">
                                <span class="material-symbols-outlined text-[20px]">shopping_bag</span> Orders
                            </a>
                            <hr class="my-4 border-outline-variant">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-error hover:bg-error-container hover:text-on-error-container transition">
                                    <span class="material-symbols-outlined text-[20px]">logout</span> Log Out
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                {{-- Order List --}}
                <div class="flex-1">
                    @if(session('success'))
                        <div class="mb-6 bg-secondary-container text-on-secondary-container p-4 rounded-lg flex items-center gap-3">
                            <span class="material-symbols-outlined">check_circle</span>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-16 text-center">
                            <div class="w-20 h-20 bg-surface-container mx-auto rounded-full flex items-center justify-center mb-6">
                                <span class="material-symbols-outlined text-secondary text-3xl">inbox</span>
                            </div>
                            <h3 class="text-xl font-bold text-on-surface mb-2">No orders yet</h3>
                            <p class="text-sm text-secondary mb-6">You haven't placed any orders yet. Explore our Ayurvedic range!</p>
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-on-primary-fixed-variant transition">
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
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'shipped'    => 'bg-purple-100 text-purple-800',
                                        'delivered'  => 'bg-emerald-100 text-emerald-800',
                                        'cancelled'  => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                                    {{-- Order Header --}}
                                    <div class="px-6 py-4 border-b border-outline-variant bg-surface-container/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-3 mb-1">
                                                <span class="font-bold text-on-surface text-lg">#{{ $order->order_number }}</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-secondary">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="text-right">
                                                <p class="text-xs text-secondary">Total</p>
                                                <p class="font-bold text-on-surface text-lg">₹{{ number_format($order->grand_total, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Order Items --}}
                                    <div class="px-6 py-4">
                                        <div class="space-y-3">
                                            @foreach($order->items->take(3) as $item)
                                                <div class="flex items-center gap-4">
                                                    <div class="w-14 h-14 bg-surface-container rounded-lg border border-outline-variant overflow-hidden flex-shrink-0">
                                                        @if($item->product && $item->product->main_image)
                                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center">
                                                                <span class="material-symbols-outlined text-outline text-[20px]">image</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-on-surface line-clamp-1">{{ $item->product_name }}</p>
                                                        <p class="text-xs text-secondary">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</p>
                                                    </div>
                                                    <p class="text-sm font-bold text-on-surface">₹{{ number_format($item->total, 2) }}</p>
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <p class="text-xs text-secondary mt-2">+ {{ $order->items->count() - 3 }} more item(s)</p>
                                            @endif
                                        </div>

                                        {{-- Payment & Delivery Info --}}
                                        <div class="mt-4 pt-4 border-t border-outline-variant flex flex-wrap gap-4 text-sm text-secondary">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px]">payments</span>
                                                {{ strtoupper($order->payment_method) }}
                                                <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                                    ({{ ucfirst($order->payment_status) }})
                                                </span>
                                            </div>
                                            @if($order->address)
                                                <div class="flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                                    {{ $order->address->city }}, {{ $order->address->state }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    @if($order->order_status === 'delivered')
                                        <div class="px-6 pb-4">
                                            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:underline">
                                                <span class="material-symbols-outlined text-[16px]">repeat</span>
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
        </div>
    </div>
</x-app-layout>
