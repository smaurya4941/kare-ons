<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>

    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', setting('site_name', 'Kare ONS Herbals') . ' | Clinical Excellence in Botanical Medicine')</title>
    @if(setting('favicon'))
    <link rel="icon" type="image/png" href="{{ image_url(setting('favicon')) }}">
    @else
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- SEO Meta Tags --}}
    @php
    $siteName = setting('site_name', 'Kare ONS Herbals');
    $defaultDesc = strip_tags(setting('seo_meta_description') ?: setting('about_text') ?: 'Premium Ayurvedic and herbal wellness products by ' . $siteName . '.');
    $metaDescription = \Illuminate\Support\Str::limit(trim(strip_tags($__env->yieldContent('meta_description', $defaultDesc))), 160);
    $metaKeywords = trim($__env->yieldContent('meta_keywords', setting('seo_meta_keywords', '')));
    $pageTitle = trim($__env->yieldContent('title', $siteName));
    $ogImage = trim($__env->yieldContent('og_image', setting('logo') ? image_url(setting('logo')) : asset('images/logo.png')));
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    @if($metaKeywords)
    <meta name="keywords" content="{{ $metaKeywords }}">@endif
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $__env->yieldContent('og_type', 'website') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}">@endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Plus+Jakarta+Sans:wght@600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />

    <style>
        @keyframes logoPulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .animate-logo-pulse {
            animation: logoPulse 2s ease-in-out infinite;
        }
        #global-preloader {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background-color: #fcf9f4; /* Sand Canvas */
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-on-background font-body-md selection:bg-secondary-fixed selection:text-on-secondary-fixed">
    <!-- Global Page Preloader -->
    <div id="global-preloader">
        <img src="{{ asset('images/page-loader.png') }}" alt="Loading Kareons..." class="w-64 md:w-80 h-auto animate-logo-pulse">
    </div>

    <!-- Toast notifications -->
    <div id="toast-container" class="fixed top-16 right-4 z-[100] flex flex-col gap-2 w-[calc(100%-2rem)] max-w-sm pointer-events-none" aria-live="polite" aria-atomic="true"></div>

    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-outline-variant transition-all duration-300" id="navbar">
        <div class="flex justify-between items-center max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop h-14">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ setting('logo') ? image_url(setting('logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'Kare ONS Herbals') }} Logo" class="h-9 w-auto object-contain">
            </a>
            <div class="hidden md:flex items-center gap-6 h-full">
                <a class="{{ request()->routeIs('home') ? 'nav-link-active' : 'text-on-surface hover:text-brand-gold-dark transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('home') }}">Home</a>
                <a class="{{ request()->routeIs('shop.index') ? 'nav-link-active' : 'text-on-surface hover:text-brand-gold-dark transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('shop.index') }}">Shop</a>
                <div class="relative group h-full flex items-center">
                    <button class="flex items-center gap-1 text-on-surface font-medium hover:text-brand-gold-dark transition-colors duration-200 text-sm">
                        Categories
                        <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>
                    <div class="absolute top-full left-0 w-64 bg-white shadow-lg border border-outline-variant opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 rounded-b-lg overflow-hidden">
                        <div class="flex flex-col py-1">
                            @foreach($headerCategories ?? [] as $navCat)
                            <a class="px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container hover:text-brand-gold-dark transition-colors" href="{{ route('shop.index', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a class="{{ request()->routeIs('about') ? 'nav-link-active' : 'text-on-surface hover:text-brand-gold-dark transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('about') }}">About</a>
                <a class="{{ request()->routeIs('blog.index') ? 'nav-link-active' : 'text-on-surface hover:text-brand-gold-dark transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('blog.index') }}">Blog</a>
                <a class="{{ request()->routeIs('contact') ? 'nav-link-active' : 'text-on-surface hover:text-brand-gold-dark transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('contact') }}">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                {{-- Live search autocomplete --}}
                <div
                    class="relative"
                    x-data="productSearch({ endpoint: '{{ route('search.suggest') }}', shopUrl: '{{ route('shop.index') }}' })"
                    @keydown.escape.window="closeSearch()"
                    @click.outside="closeSearch()">
                    <button
                        type="button"
                        class="text-on-surface hover:text-brand-gold-dark transition flex items-center"
                        aria-label="Search products"
                        x-show="!open"
                        @click="openSearch()">
                        <span class="material-symbols-outlined">search</span>
                    </button>

                    {{-- Expanding search field --}}
                    <div
                        x-show="open"
                        x-cloak
                        x-transition.opacity
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-50">
                        <div class="flex items-center bg-white border border-outline-variant rounded-full shadow-lg overflow-hidden w-[78vw] max-w-sm md:w-80">
                            <span class="material-symbols-outlined text-on-surface-variant pl-3 text-[20px]">search</span>
                            <input
                                x-ref="input"
                                type="text"
                                x-model="query"
                                @input="onInput()"
                                @keydown.arrow-down.prevent="highlightNext()"
                                @keydown.arrow-up.prevent="highlightPrev()"
                                @keydown.enter.prevent="onEnter()"
                                placeholder="Search herbal products..."
                                autocomplete="off"
                                class="flex-1 border-0 focus:ring-0 text-sm py-2.5 px-3 bg-transparent">
                            <button
                                type="button"
                                class="pr-3 text-on-surface-variant hover:text-error transition"
                                aria-label="Close search"
                                @click="closeSearch()">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </div>

                        {{-- Results dropdown --}}
                        @include('partials.search-results', ['panelClass' => 'right-0 mt-2 w-[78vw] max-w-sm md:w-80'])
                    </div>
                </div>
                @php
                $wishlistCount = Auth::check() ? Auth::user()->wishlists()->count() : 0;
                @endphp
                <a href="{{ route('wishlist.index') }}" class="relative text-on-surface hover:text-brand-gold-dark transition" title="Wishlist" aria-label="Wishlist">
                    <span class="material-symbols-outlined">favorite</span>
                    <span id="wishlist-count" class="absolute -top-1 -right-1 bg-brand-gold text-brand-forest text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center {{ $wishlistCount > 0 ? '' : 'hidden' }}">{{ $wishlistCount }}</span>
                </a>
                <a href="{{ route('cart.index') }}" class="relative text-on-surface hover:text-brand-gold-dark transition">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    @php
                    $cartCount = 0;
                    if(Auth::check()) {
                    $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                    } else {
                    $cartCount = \App\Models\CartItem::where('session_id', Session::getId())->sum('quantity');
                    }
                    @endphp
                    <span id="cart-count" class="absolute -top-1 -right-1 bg-brand-gold text-brand-forest text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
                </a>
                @auth
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="text-on-surface hover:text-brand-gold-dark transition ml-2 flex items-center gap-1">
                    <span class="material-symbols-outlined">account_circle</span>
                    <span class="text-sm font-medium hidden md:inline">Account</span>
                </a>
                @else
                <div class="flex items-center gap-4 ml-2">
                    <a href="{{ route('login') }}" class="text-on-surface hover:text-brand-gold-dark transition flex items-center gap-1">
                        <span class="material-symbols-outlined">login</span>
                        <span class="text-sm font-medium hidden md:inline">Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="bg-brand-forest text-white hover:bg-brand-gold hover:text-brand-forest transition px-4 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        <span class="hidden md:inline">Register</span>
                    </a>
                </div>
                @endauth
                <button id="mobile-menu-btn" class="md:hidden text-on-surface ml-2">
                    <span id="mobile-menu-icon" class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-outline-variant absolute top-14 left-0 w-full shadow-lg h-[calc(100vh-56px)] overflow-y-auto">
            <div class="flex flex-col py-4 px-margin-mobile">
                <a class="{{ request()->routeIs('home') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium border-b border-surface-container" href="{{ route('home') }}">Home</a>
                <a class="{{ request()->routeIs('shop.index') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium border-b border-surface-container" href="{{ route('shop.index') }}">Shop</a>

                <div class="py-3 border-b border-surface-container">
                    <p class="text-sm font-medium text-on-surface mb-2">Categories</p>
                    <div class="flex flex-col pl-4 gap-3">
                        @foreach($headerCategories ?? [] as $navCat)
                        <a class="text-sm text-on-surface-variant hover:text-brand-gold-dark" href="{{ route('shop.index', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a>
                        @endforeach
                    </div>
                </div>

                <a class="{{ request()->routeIs('about') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium border-b border-surface-container" href="{{ route('about') }}">About</a>
                <a class="{{ request()->routeIs('blog.index') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium border-b border-surface-container" href="{{ route('blog.index') }}">Blog</a>
                <a class="{{ request()->routeIs('contact') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium" href="{{ route('contact') }}">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @isset($header)
    <header class="bg-surface shadow-sm border-b border-outline-variant mt-14">
        <div class="max-w-container-max mx-auto py-5 px-margin-mobile md:px-margin-desktop">
            {{ $header }}
        </div>
    </header>
    @endisset

    <main class="flex-grow pt-14">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer class="relative w-full bg-herbal-deep text-on-primary overflow-hidden">
        {{-- Thin brand accent line --}}
        <div class="h-px w-full bg-gradient-to-r from-transparent via-secondary-fixed/70 to-transparent"></div>

        {{-- Main footer grid --}}
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-12 gap-x-gutter gap-y-6">
            {{-- Brand column --}}
            <div class="col-span-2 md:col-span-4 lg:col-span-4">
                <a class="inline-block mb-4" href="{{ route('home') }}">
                    <img src="{{ setting('logo') ? image_url(setting('logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'Kare ONS Herbals') }} Logo" class="h-12 w-auto object-contain bg-white rounded-lg p-1.5">
                </a>
                <p class="text-on-primary/70 font-body-md text-label-md leading-relaxed max-w-xs mb-5">
                    {{ \Illuminate\Support\Str::limit(strip_tags(setting('about_text', 'Pure, potent Ayurvedic wellness — 5,000 years of Vedic wisdom, made for modern life.')), 130) }}
                </p>
                <div class="flex gap-2.5">
                    @if(setting('instagram_url'))
                    <a class="w-9 h-9 rounded-full bg-on-primary/5 border border-on-primary/15 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed hover:border-secondary-fixed transition-all" href="{{ setting('instagram_url') }}" target="_blank" rel="noopener" title="Instagram" aria-label="Follow us on Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    @endif
                    @if(setting('twitter_url'))
                    <a class="w-9 h-9 rounded-full bg-on-primary/5 border border-on-primary/15 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed hover:border-secondary-fixed transition-all" href="{{ setting('twitter_url') }}" target="_blank" rel="noopener" title="Twitter / X" aria-label="Follow us on Twitter">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    @endif
                    @if(setting('linkedin_url'))
                    <a class="w-9 h-9 rounded-full bg-on-primary/5 border border-on-primary/15 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed hover:border-secondary-fixed transition-all" href="{{ setting('linkedin_url') }}" target="_blank" rel="noopener" title="LinkedIn" aria-label="Connect on LinkedIn">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                    @endif
                    @if(setting('site_email'))
                    <a class="w-9 h-9 rounded-full bg-on-primary/5 border border-on-primary/15 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed hover:border-secondary-fixed transition-all" href="mailto:{{ setting('site_email') }}" title="Email us" aria-label="Email us">
                        <span class="material-symbols-outlined text-[18px]">mail</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Shop by category (dynamic) --}}
            <div class="col-span-1 md:col-span-1 lg:col-span-2">
                <h5 class="text-secondary-fixed font-label-md text-label-sm uppercase tracking-widest mb-3">Shop</h5>
                <ul class="space-y-2">
                    <li><a class="footer-link" href="{{ route('shop.index') }}">All Products</a></li>
                    @foreach(($headerCategories ?? [])->take(4) as $navCat)
                    <li><a class="footer-link" href="{{ route('shop.index', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Company --}}
            <div class="col-span-1 md:col-span-1 lg:col-span-2">
                <h5 class="text-secondary-fixed font-label-md text-label-sm uppercase tracking-widest mb-3">Company</h5>
                <ul class="space-y-2">
                    <li><a class="footer-link" href="{{ route('about') }}">About Us</a></li>
                    <li><a class="footer-link" href="{{ route('blog.index') }}">Journal</a></li>
                    <li><a class="footer-link" href="{{ route('contact') }}">Contact</a></li>
                    @foreach($footerPages ?? [] as $page)
                    <li><a class="footer-link" href="{{ route('page.show', $page->slug) }}">{{ $page->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Customer care --}}
            <div class="col-span-1 md:col-span-1 lg:col-span-2">
                <h5 class="text-secondary-fixed font-label-md text-label-sm uppercase tracking-widest mb-3">Account</h5>
                <ul class="space-y-2">
                    @auth
                    <li><a class="footer-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a class="footer-link" href="{{ route('orders.index') }}">My Orders</a></li>
                    <li><a class="footer-link" href="{{ route('wishlist.index') }}">Wishlist</a></li>
                    @else
                    <li><a class="footer-link" href="{{ route('login') }}">Sign In</a></li>
                    <li><a class="footer-link" href="{{ route('register') }}">Create Account</a></li>
                    @endauth
                    <li><a class="footer-link" href="{{ route('cart.index') }}">My Cart</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-span-1 md:col-span-1 lg:col-span-2">
                <h5 class="text-secondary-fixed font-label-md text-label-sm uppercase tracking-widest mb-3">Contact</h5>
                <ul class="space-y-2">
                    @if(setting('site_phone'))
                    <li><a class="footer-link" href="tel:{{ setting('site_phone') }}">{{ setting('site_phone') }}</a></li>
                    @endif
                    @if(setting('site_email'))
                    <li><a class="footer-link break-all" href="mailto:{{ setting('site_email') }}">{{ setting('site_email') }}</a></li>
                    @endif
                    @if(setting('address'))
                    <li class="text-on-primary/70 font-body-md text-label-md leading-relaxed">{{ setting('address') }}</li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-on-primary/10">
            <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                <p class="text-on-primary/60 text-label-sm text-center sm:text-left">
                    {{ setting('copyright_text', '© ' . date('Y') . ' ' . setting('site_name', 'Kare ONS Herbals') . '. All rights reserved.') }}
                </p>
                <div class="flex items-center gap-1.5" aria-label="Accepted payment methods">
                    @foreach(['Visa', 'Mastercard', 'UPI', 'RuPay'] as $pay)
                    <span class="px-2 py-0.5 rounded bg-on-primary/5 border border-on-primary/10 text-on-primary/70 text-[10px] font-semibold tracking-wide">{{ $pay }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Global Preloader Logic
        window.addEventListener('load', () => {
            const preloader = document.getElementById('global-preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.visibility = 'hidden';
                    preloader.style.display = 'none';
                }, 500);
            }
        });

        document.addEventListener('click', (e) => {
            const target = e.target.closest('a');
            if (target && target.href && !target.hasAttribute('download') && target.hostname === window.location.hostname && !target.href.includes('#') && target.target !== '_blank') {
                const preloader = document.getElementById('global-preloader');
                if (preloader) {
                    preloader.style.display = 'flex';
                    // Small delay to allow browser to calculate layout before fading in
                    setTimeout(() => {
                        preloader.style.visibility = 'visible';
                        preloader.style.opacity = '1';
                    }, 10);
                }
            }
        });

        // Micro-interactions and scroll effects
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.clinical-card');

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        entry.target.classList.remove('opacity-0', 'translate-y-10');
                    }
                });
            }, observerOptions);

            cards.forEach(card => {
                card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
                observer.observe(card);
            });
        });

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (navbar && window.scrollY > 50) {
                navbar.classList.add('shadow-md');
            } else if (navbar) {
                navbar.classList.remove('shadow-md');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuIcon = document.getElementById('mobile-menu-icon');

        if (mobileMenuBtn && mobileMenu && mobileMenuIcon) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenuIcon.textContent = 'menu';
                    document.body.style.overflow = '';
                } else {
                    mobileMenuIcon.textContent = 'close';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
                }
            });
        }

        async function toggleWishlist(productId) {
            try {
                const response = await fetch(`/wishlist/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                });

                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                const data = await response.json();
                if (data.status === 'added') {
                    document.querySelectorAll(`.wishlist-icon-${productId}`).forEach(icon => {
                        icon.style.fontVariationSettings = "'FILL' 1";
                        icon.classList.add('text-brand-gold-dark');
                        icon.classList.remove('text-on-surface-variant');
                        if (icon.parentElement.tagName === 'BUTTON') {
                            icon.parentElement.classList.add('border-brand-gold-dark', 'bg-brand-gold/10');
                            icon.parentElement.classList.remove('border-soft-border');
                        }
                    });
                } else if (data.status === 'removed') {
                    document.querySelectorAll(`.wishlist-icon-${productId}`).forEach(icon => {
                        icon.style.fontVariationSettings = "'FILL' 0";
                        icon.classList.remove('text-brand-gold-dark');
                        icon.classList.add('text-on-surface-variant');
                        if (icon.parentElement.tagName === 'BUTTON') {
                            icon.parentElement.classList.remove('border-brand-gold-dark', 'bg-brand-gold/10');
                            icon.parentElement.classList.add('border-soft-border');
                        }
                    });
                }
                if (typeof data.wishlist_count !== 'undefined') updateWishlistCount(data.wishlist_count);
                if (data.status === 'added') {
                    showToast(data.message || 'Saved to your wishlist.', 'success', {
                        title: 'Added to Wishlist',
                        action: {
                            label: 'View Wishlist',
                            url: '{{ route('wishlist.index') }}'
                        },
                    });
                } else if (data.status === 'removed') {
                    showToast(data.message || 'Removed from your wishlist.', 'info', {
                        title: 'Removed from Wishlist',
                    });
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                showToast('Something went wrong. Please try again.', 'error');
            }
        }

        // -------------------------------------------------------------------------
        // Toast notifications
        // -------------------------------------------------------------------------
        /**
         * Show a toast notification (top-right, auto-dismissing).
         *
         * @@param {string} message  Body text.
         * @@param {string} type     'success' | 'error' | 'info'
         * @@param {object} [options]
         * @@param {string} [options.title]     Bold heading, e.g. "Added to Cart".
         * @@param @{{label:string,url:string}} [options.action]  Optional link button.
         * @@param {number} [options.duration]  Auto-dismiss ms (default 3500).
         */
        function showToast(message, type = 'success', options = {}) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const {
                title = null, action = null, duration = 3500
            } = options || {};

            const palette = {
                success: {
                    bg: 'bg-secondary',
                    icon: 'check_circle'
                },
                error: {
                    bg: 'bg-error',
                    icon: 'error'
                },
                info: {
                    bg: 'bg-on-surface',
                    icon: 'info'
                },
            } [type] || {
                bg: 'bg-on-surface',
                icon: 'info'
            };

            // Escape any user/server-supplied text before injecting as HTML.
            const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            } [c]));

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto ${palette.bg} text-white rounded-lg shadow-lg px-4 py-3 flex items-start gap-3 text-sm translate-x-4 opacity-0 transition-all duration-300`;
            toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

            const titleHtml = title ?
                `<p class="font-semibold leading-tight">${esc(title)}</p>` :
                '';
            const messageHtml = message ?
                `<p class="${title ? 'text-white/90 mt-0.5' : 'font-medium'} leading-snug">${esc(message)}</p>` :
                '';
            const actionHtml = (action && action.url && action.label) ?
                `<a href="${esc(action.url)}" class="inline-flex items-center gap-1 mt-1.5 text-white font-semibold underline underline-offset-2 hover:text-white/80 transition">${esc(action.label)}<span class="material-symbols-outlined text-[16px]">chevron_right</span></a>` :
                '';

            toast.innerHTML = `
                <span class="material-symbols-outlined text-[20px] mt-px flex-shrink-0" style="font-variation-settings:'FILL' 1;">${palette.icon}</span>
                <div class="flex-1 min-w-0">${titleHtml}${messageHtml}${actionHtml}</div>
                <button type="button" class="flex-shrink-0 -mr-1 -mt-0.5 text-white/70 hover:text-white transition" aria-label="Dismiss">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>`;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-4', 'opacity-0');
            });

            const dismiss = () => {
                toast.classList.add('translate-x-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            };

            toast.querySelector('button[aria-label="Dismiss"]')?.addEventListener('click', dismiss);
            setTimeout(dismiss, duration);
        }
        // Expose globally so Alpine components / other scripts can trigger toasts.
        window.showToast = showToast;

        // -------------------------------------------------------------------------
        // AJAX add-to-cart (progressive enhancement of any form.js-cart-form)
        // -------------------------------------------------------------------------
        document.addEventListener('submit', async (e) => {
            const form = e.target.closest('form.js-cart-form');
            if (!form) return;
            e.preventDefault();

            // Use the button that actually triggered the submit (e.g. "Add to Cart" vs "Buy Now")
            const btn = e.submitter && form.contains(e.submitter) ? e.submitter : form.querySelector('[type="submit"]');
            if (btn?.dataset.loading === '1') return; // guard against double submit
            const originalHtml = btn ? btn.innerHTML : null;
            if (btn) {
                btn.dataset.loading = '1';
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
            }

            // Passing the submitter includes its name/value (action=cart|buy) in the payload.
            // Fall back to appending it manually for browsers lacking the FormData submitter arg.
            let payload;
            try {
                payload = new FormData(form, btn);
            } catch (_) {
                payload = new FormData(form);
            }
            if (btn?.name && !payload.has(btn.name)) {
                payload.append(btn.name, btn.value);
            }

            // Use getAttribute: form.action is shadowed by the `action`-named submit buttons
            const formAction = form.getAttribute('action');

            try {
                const response = await fetch(formAction, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: payload,
                });

                const data = await response.json().catch(() => ({}));

                if (typeof data.cart_count !== 'undefined') updateCartCount(data.cart_count);

                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                if (response.ok && data.status === 'success') {
                    showToast(data.message || 'Added to your cart.', 'success', {
                        title: 'Added to Cart',
                        action: {
                            label: 'View Cart',
                            url: '{{ route('cart.index') }}'
                        },
                    });
                } else {
                    showToast(data.message || 'Could not add to cart.', 'error');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showToast('Something went wrong. Please try again.', 'error');
            } finally {
                if (btn) {
                    btn.dataset.loading = '0';
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            }
        });

        function updateCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (!badge) return;
            badge.textContent = count;
            badge.classList.toggle('hidden', !(count > 0));
        }

        function updateWishlistCount(count) {
            const badge = document.getElementById('wishlist-count');
            if (!badge) return;
            badge.textContent = count;
            badge.classList.toggle('hidden', !(count > 0));
        }

        // Surface server-side flash messages as toasts
        document.addEventListener('DOMContentLoaded', () => {
            // Structured toast (preferred): supports title + action link.
            @if(session('toast'))
            showToast(
                @json(session('toast')['message'] ?? ''),
                @json(session('toast')['type'] ?? 'success'),
                @json(['title' => session('toast')['title'] ?? null, 'action' => session('toast')['action'] ?? null])
            );
            @endif
            // Legacy plain flash messages.
            @if(session('success')) showToast(@json(session('success')), 'success');
            @endif
            @if(session('error')) showToast(@json(session('error')), 'error');
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>