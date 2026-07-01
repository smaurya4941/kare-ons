<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-on-surface leading-tight">
            {{ $title ?? 'Dashboard' }}
        </h2>
    </x-slot>

    <div class="py-12 bg-surface-container-lowest min-h-screen">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- Sidebar --}}
                <div class="w-full lg:w-72 flex-shrink-0">
                    <div class="bg-surface rounded-xl border border-outline-variant p-4 sticky top-24 shadow-sm">
                        <div class="flex items-center gap-4 p-4 mb-4 border-b border-outline-variant">
                            <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center font-bold text-2xl">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs text-secondary mb-0.5">Hello,</p>
                                <h3 class="font-bold text-on-surface line-clamp-1 text-lg">{{ Auth::user()->name }}</h3>
                            </div>
                        </div>

                        <nav class="space-y-1">
                            @php
                                $currentRoute = request()->route()->getName();
                            @endphp
                            
                            <a href="{{ route('orders.index') }}" class="flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition {{ \Illuminate\Support\Str::startsWith($currentRoute, 'orders.') ? 'bg-primary-fixed text-primary border border-primary' : 'text-secondary hover:bg-surface-container hover:text-on-surface border border-transparent' }}">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[24px] {{ \Illuminate\Support\Str::startsWith($currentRoute, 'orders.') ? 'text-primary' : 'text-outline' }}">package_2</span> 
                                    <span class="text-base">My Orders</span>
                                </div>
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </a>
                            
                            <div class="pt-4 pb-2 px-4 flex items-center gap-3">
                                <span class="material-symbols-outlined text-[24px] text-outline">person</span> 
                                <span class="font-bold text-secondary uppercase text-xs tracking-wider">Account Settings</span>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-4 py-3 ml-4 rounded-lg text-sm font-medium transition {{ $currentRoute === 'profile.edit' ? 'bg-primary-fixed text-primary border border-primary' : 'text-secondary hover:bg-surface-container hover:text-on-surface border border-transparent' }}">
                                <span>Profile Information</span>
                            </a>
                            <a href="{{ route('addresses.index') }}" class="flex items-center justify-between px-4 py-3 ml-4 rounded-lg text-sm font-medium transition {{ \Illuminate\Support\Str::startsWith($currentRoute, 'addresses.') ? 'bg-primary-fixed text-primary border border-primary' : 'text-secondary hover:bg-surface-container hover:text-on-surface border border-transparent' }}">
                                <span>Manage Addresses</span>
                            </a>
                            
                            <div class="pt-4 pb-2 px-4 flex items-center gap-3">
                                <span class="material-symbols-outlined text-[24px] text-outline">account_balance_wallet</span> 
                                <span class="font-bold text-secondary uppercase text-xs tracking-wider">My Stuff</span>
                            </div>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between px-4 py-3 ml-4 rounded-lg text-sm font-medium transition {{ $currentRoute === 'wishlist.index' ? 'bg-primary-fixed text-primary border border-primary' : 'text-secondary hover:bg-surface-container hover:text-on-surface border border-transparent' }}">
                                <span>My Wishlist</span>
                            </a>
                            
                            <hr class="my-4 border-outline-variant">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-secondary hover:text-error hover:bg-error-container transition border border-transparent">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-[24px] text-outline">power_settings_new</span> 
                                        <span class="text-base">Logout</span>
                                    </div>
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="flex-1">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
