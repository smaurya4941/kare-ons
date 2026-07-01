<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | {{ setting('site_name', 'Kare Ons Herbal') }}</title>
    @if(setting('favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 h-full border-r border-slate-800 transition-all duration-300">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950/50">
            <span class="text-xl font-bold tracking-tight text-white">Kare Ons <span class="font-light text-slate-400">Admin</span></span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                Dashboard
            </a>
            
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Catalog</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">category</span>
                Categories
            </a>
            <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.brands.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">branding_watermark</span>
                Brands
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.inventory.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">warehouse</span>
                Inventory
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">reviews</span>
                Reviews
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sales</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                Orders
                @php $pendingOrdersCount = \App\Models\Order::where('order_status', 'pending')->count(); @endphp
                @if($pendingOrdersCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.returns.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.returns.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">assignment_return</span>
                Return Requests
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">group</span>
                Customers
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">local_offer</span>
                Coupons
            </a>
            
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Analytics</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">bar_chart</span>
                Reports
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Content</p>
            </div>
            <a href="{{ route('admin.blogs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.blogs.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">article</span>
                Blog Posts
            </a>
            <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.banners.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">view_carousel</span>
                Banners
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.testimonials.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">reviews</span>
                Testimonials
            </a>
            <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.pages.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">description</span>
                Pages
            </a>
            <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.media.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">perm_media</span>
                Media Library
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</p>
            </div>
            <a href="{{ route('admin.shipping.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.shipping.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                Shipping
            </a>
            <a href="{{ route('admin.payment_methods.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.payment_methods.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">payments</span>
                Payment Methods
            </a>
            <a href="{{ route('admin.taxes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.taxes.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">account_balance</span>
                Taxes
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition' }}">
                <span class="material-symbols-outlined text-[20px]">settings</span>
                Settings
            </a>
        </nav>

        <!-- User profile -->
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-slate-700 flex justify-center items-center font-bold text-sm text-white flex-shrink-0">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@kareons.com' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex justify-center items-center rounded-lg text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition" title="Log Out">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 z-10">
            <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span> View Store
                </a>
                <div class="h-6 w-px bg-gray-200"></div>
                <button class="text-gray-500 hover:text-gray-700 focus:outline-none flex items-center">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded shadow-sm flex items-start" x-data="{ show: true }" x-show="show">
                    <span class="material-symbols-outlined text-emerald-500 mr-3">check_circle</span>
                    <div class="flex-1">
                        <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm flex items-start" x-data="{ show: true }" x-show="show">
                    <span class="material-symbols-outlined text-red-500 mr-3">error</span>
                    <div class="flex-1">
                        <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
