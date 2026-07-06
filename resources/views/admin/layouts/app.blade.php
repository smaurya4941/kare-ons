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
    @vite(['resources/css/admin.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Premium sidebar chrome */
        .admin-sidebar {
            background:
                radial-gradient(120% 60% at 0% 0%, rgba(201,164,82,0.08) 0%, rgba(201,164,82,0) 55%),
                linear-gradient(180deg, #16302a 0%, #122622 55%, #0f201c 100%);
        }
        .admin-nav {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.14) transparent;
        }
        .admin-nav::-webkit-scrollbar { width: 6px; }
        .admin-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.12);
            border-radius: 9999px;
        }
        .admin-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.22); }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.5rem 0.7rem;
            border-radius: 0.6rem;
            font-size: 12.5px;
            font-weight: 500;
            letter-spacing: 0.01em;
            color: rgba(255,255,255,0.62);
            transition: background-color .15s ease, color .15s ease, transform .15s ease;
        }
        .nav-link .material-symbols-outlined {
            font-size: 19px;
            color: rgba(255,255,255,0.55);
            transition: color .15s ease;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.055);
        }
        .nav-link:hover .material-symbols-outlined { color: rgba(255,255,255,0.9); }

        .nav-link.is-active {
            color: #fff;
            background: linear-gradient(90deg, rgba(201,164,82,0.22) 0%, rgba(201,164,82,0.06) 100%);
            box-shadow: inset 0 0 0 1px rgba(201,164,82,0.18);
        }
        .nav-link.is-active .material-symbols-outlined {
            color: #d8b768;
            font-variation-settings: 'FILL' 1;
        }
        .nav-link.is-active::before {
            content: '';
            position: absolute;
            left: -0.7rem;
            top: 50%;
            transform: translateY(-50%);
            height: 1.15rem;
            width: 3px;
            border-radius: 9999px;
            background: #c9a452;
            box-shadow: 0 0 8px rgba(201,164,82,0.6);
        }

        .nav-section {
            padding: 0.85rem 0.7rem 0.3rem;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(201,164,82,0.6);
        }
        .nav-badge {
            margin-left: auto;
            min-width: 1.15rem;
            height: 1.15rem;
            padding: 0 0.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            background: #e2483d;
            border-radius: 9999px;
            box-shadow: 0 0 0 2px rgba(226,72,61,0.18);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="admin-sidebar w-[248px] text-white flex flex-col flex-shrink-0 h-full border-r border-white/[0.06] shadow-[8px_0_30px_-18px_rgba(0,0,0,0.6)]">
        <!-- Brand -->
        <div class="h-16 flex items-center gap-3 px-5 border-b border-white/[0.06]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-gold to-brand-gold-dark text-brand-forest-dark flex items-center justify-center font-extrabold text-sm shadow-lg shadow-brand-gold/20 flex-shrink-0">
                KO
            </div>
            <div class="leading-tight">
                <p class="text-[14px] font-semibold tracking-tight text-white">Kare Ons</p>
                <p class="text-[10px] font-medium uppercase tracking-[0.18em] text-brand-gold/70">Admin Suite</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav flex-1 overflow-y-auto py-3 px-3 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">notifications</span>
                Notifications
                @php $unreadNotifications = \App\Models\AdminNotification::unreadCount(); @endphp
                @if($unreadNotifications > 0)
                    <span class="nav-badge">{{ $unreadNotifications }}</span>
                @endif
            </a>

            <p class="nav-section">Catalog</p>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">inventory_2</span>
                Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">category</span>
                Categories
            </a>
            <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">branding_watermark</span>
                Brands
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">warehouse</span>
                Inventory
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">reviews</span>
                Reviews
            </a>

            <p class="nav-section">Sales</p>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                Orders
                @php $pendingOrdersCount = \App\Models\Order::where('order_status', 'pending')->count(); @endphp
                @if($pendingOrdersCount > 0)
                    <span class="nav-badge">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.returns.index') }}" class="nav-link {{ request()->routeIs('admin.returns.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">assignment_return</span>
                Return Requests
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">group</span>
                Customers
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">contact_support</span>
                Inquiries
                @php $unreadInquiries = \App\Models\ContactInquiry::where('is_read', false)->count(); @endphp
                @if($unreadInquiries > 0)
                    <span class="nav-badge">{{ $unreadInquiries }}</span>
                @endif
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">local_offer</span>
                Coupons
            </a>

            <p class="nav-section">Analytics</p>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">bar_chart</span>
                Reports
            </a>
            <a href="{{ route('admin.activity.index') }}" class="nav-link {{ request()->routeIs('admin.activity.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">history</span>
                Activity Log
            </a>

            <p class="nav-section">Content</p>
            <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">article</span>
                Blog Posts
            </a>
            <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">view_carousel</span>
                Banners
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">reviews</span>
                Testimonials
            </a>
            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">description</span>
                Pages
            </a>
            <a href="{{ route('admin.media.index') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">perm_media</span>
                Media Library
            </a>

            <p class="nav-section">System</p>
            <a href="{{ route('admin.shipping.index') }}" class="nav-link {{ request()->routeIs('admin.shipping.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                Shipping
            </a>
            <a href="{{ route('admin.payment_methods.index') }}" class="nav-link {{ request()->routeIs('admin.payment_methods.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">payments</span>
                Payment Methods
            </a>
            <a href="{{ route('admin.taxes.index') }}" class="nav-link {{ request()->routeIs('admin.taxes.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">account_balance</span>
                Taxes
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>
        </nav>

        <!-- User profile -->
        <div class="border-t border-white/[0.06] p-3">
            <div class="flex items-center justify-between gap-2 rounded-xl bg-white/[0.04] hover:bg-white/[0.07] transition px-2.5 py-2">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-gold to-brand-gold-dark text-brand-forest-dark flex justify-center items-center font-bold text-xs flex-shrink-0 shadow-md shadow-brand-gold/20">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12.5px] font-semibold text-white truncate leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10.5px] text-white/50 truncate leading-tight">{{ Auth::user()->email ?? 'admin@kareons.com' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="w-7 h-7 flex justify-center items-center rounded-lg text-white/50 hover:bg-red-500/15 hover:text-red-400 transition" title="Log Out">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
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
                @include('admin.partials.notification-bell')
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
