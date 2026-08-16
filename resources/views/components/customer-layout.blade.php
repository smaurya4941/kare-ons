<x-app-layout>
    <div class="py-6 bg-surface-container-lowest min-h-screen">
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
            <div class="flex flex-col lg:flex-row gap-6">
                
                {{-- Sidebar --}}
                <div class="w-full lg:w-72 flex-shrink-0">
                    <div class="bg-surface rounded-xl border border-outline-variant p-3 sticky top-20 shadow-sm">
                        <div class="flex items-center gap-3 p-3 mb-3 border-b border-outline-variant">
                            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[11px] text-on-surface-variant mb-0.5">Hello,</p>
                                <h3 class="font-bold text-on-surface line-clamp-1 text-base leading-tight">{{ Auth::user()->name }}</h3>
                            </div>
                        </div>

                        <nav class="space-y-1">
                            @php
                                $currentRoute = request()->route()->getName();
                            @endphp
                            
                            <a href="{{ route('orders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition {{ \Illuminate\Support\Str::startsWith($currentRoute, 'orders.') ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[20px] {{ \Illuminate\Support\Str::startsWith($currentRoute, 'orders.') ? 'text-primary' : 'text-outline' }}">package_2</span> 
                                    <span>My Orders</span>
                                </div>
                                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                            </a>
                            
                            <div class="pt-3 pb-1.5 px-3 flex items-center gap-2.5 mt-2">
                                <span class="material-symbols-outlined text-[18px] text-outline">person</span> 
                                <span class="font-bold text-on-surface-variant uppercase text-[10px] tracking-wider">Account Settings</span>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-3 py-2 ml-2 rounded-lg text-sm font-medium transition {{ $currentRoute === 'profile.edit' ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span>Profile Information</span>
                            </a>
                            <a href="{{ route('addresses.index') }}" class="flex items-center justify-between px-3 py-2 ml-2 rounded-lg text-sm font-medium transition {{ \Illuminate\Support\Str::startsWith($currentRoute, 'addresses.') ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span>Manage Addresses</span>
                            </a>
                            
                            <div class="pt-3 pb-1.5 px-3 flex items-center gap-2.5 mt-2">
                                <span class="material-symbols-outlined text-[18px] text-outline">account_balance_wallet</span> 
                                <span class="font-bold text-on-surface-variant uppercase text-[10px] tracking-wider">My Stuff</span>
                            </div>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between px-3 py-2 ml-2 rounded-lg text-sm font-medium transition {{ $currentRoute === 'wishlist.index' ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span>My Wishlist</span>
                            </a>
                            
                            <hr class="my-3 border-outline-variant">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-on-surface-variant hover:text-error hover:bg-error-container transition">
                                    <div class="flex items-center gap-2.5">
                                        <span class="material-symbols-outlined text-[20px] text-outline">power_settings_new</span> 
                                        <span>Logout</span>
                                    </div>
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="flex-1">
                    @if(isset($title) && !isset($hideTitle))
                        <h2 class="font-bold text-xl text-on-surface tracking-tight mb-4">{{ $title }}</h2>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
