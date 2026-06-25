<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-on-surface leading-tight">
            {{ __('My Account') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-surface-container-lowest min-h-screen">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Sidebar -->
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
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">shopping_bag</span> Orders
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">location_on</span> Addresses
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container hover:text-on-surface transition">
                                <span class="material-symbols-outlined text-[20px]">favorite</span> Wishlist
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

                <!-- Main Content -->
                <div class="flex-1 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Widget -->
                        <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined">shopping_cart</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Total Orders</p>
                                <p class="text-2xl font-bold text-on-surface">0</p>
                            </div>
                        </div>

                        <!-- Widget -->
                        <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined">pending_actions</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Pending Orders</p>
                                <p class="text-2xl font-bold text-on-surface">0</p>
                            </div>
                        </div>

                        <!-- Widget -->
                        <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <span class="material-symbols-outlined">where_to_vote</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Saved Addresses</p>
                                <p class="text-2xl font-bold text-on-surface">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-on-surface">Recent Orders</h3>
                            <a href="#" class="text-sm font-medium text-primary hover:underline">View All</a>
                        </div>
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-surface-container mx-auto rounded-full flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-secondary text-3xl">inbox</span>
                            </div>
                            <h4 class="text-on-surface font-medium mb-2">No orders yet</h4>
                            <p class="text-sm text-secondary mb-4">You haven't placed any orders yet. Start exploring our herbal products.</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-on-primary-fixed-variant transition">
                                Browse Shop
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
