<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-on-surface leading-tight">
            My Account
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
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-secondary-container text-on-secondary-container">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span> Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">person</span> Profile
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">shopping_bag</span> My Orders
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

                {{-- Main Content --}}
                <div class="flex-1 space-y-6">
                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Total Orders</p>
                                <p class="text-2xl font-bold text-on-surface">{{ $totalOrders }}</p>
                            </div>
                        </div>

                        <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">pending_actions</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Pending / Active</p>
                                <p class="text-2xl font-bold text-on-surface">{{ $pendingOrders }}</p>
                            </div>
                        </div>

                        <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Delivered</p>
                                <p class="text-2xl font-bold text-on-surface">{{ $deliveredOrders }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Orders --}}
                    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-on-surface">Recent Orders</h3>
                            <a href="{{ route('orders.index') }}" class="text-sm font-medium text-primary hover:underline">View All →</a>
                        </div>

                        @if($recentOrders->isEmpty())
                            <div class="p-8 text-center">
                                <div class="w-16 h-16 bg-surface-container mx-auto rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-secondary text-3xl">inbox</span>
                                </div>
                                <h4 class="text-on-surface font-medium mb-2">No orders yet</h4>
                                <p class="text-sm text-secondary mb-4">You haven't placed any orders yet. Start exploring our herbal products.</p>
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-on-primary-fixed-variant transition">
                                    Browse Shop
                                </a>
                            </div>
                        @else
                            <div class="divide-y divide-outline-variant">
                                @foreach($recentOrders as $order)
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
                                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-semibold text-on-surface">#{{ $order->order_number }}</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-secondary">{{ $order->created_at->format('M d, Y') }} · {{ $order->items->count() }} item(s)</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="font-bold text-on-surface">₹{{ number_format($order->grand_total, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="px-6 py-3 border-t border-outline-variant bg-surface-container/30">
                                <a href="{{ route('orders.index') }}" class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                                    View all orders <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Links --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('shop.index') }}" class="bg-surface rounded-xl border border-outline-variant shadow-sm p-5 flex items-center gap-4 hover:border-primary hover:shadow-md transition group">
                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition">
                                <span class="material-symbols-outlined text-[20px]">storefront</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-on-surface">Shop Products</h4>
                                <p class="text-xs text-secondary">Browse our Ayurvedic range</p>
                            </div>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-surface rounded-xl border border-outline-variant shadow-sm p-5 flex items-center gap-4 hover:border-primary hover:shadow-md transition group">
                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition">
                                <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-on-surface">Edit Profile</h4>
                                <p class="text-xs text-secondary">Update your account details</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
