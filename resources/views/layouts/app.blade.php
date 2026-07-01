<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>@yield('title', setting('site_name', 'Kare ONS Herbals') . ' | Clinical Excellence in Botanical Medicine')</title>
@if(setting('favicon'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
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
    $ogImage = trim($__env->yieldContent('og_image', setting('logo') ? asset('storage/' . setting('logo')) : asset('images/logo.png')));
@endphp
<meta name="description" content="{{ $metaDescription }}">
@if($metaKeywords)<meta name="keywords" content="{{ $metaKeywords }}">@endif
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $__env->yieldContent('og_type', 'website') }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
@if($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
@if($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Plus+Jakarta+Sans:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary-fixed-variant": "#005049",
                    "surface-container-low": "#f6f3f5",
                    "surface-container": "#f0edef",
                    "tertiary-container": "#0b1c30",
                    "primary": "#000000",
                    "surface-container-high": "#eae7e9",
                    "surface-variant": "#e4e2e4",
                    "on-background": "#1b1b1d",
                    "error": "#ba1a1a",
                    "surface-dim": "#dcd9db",
                    "on-tertiary": "#ffffff",
                    "on-primary-fixed-variant": "#3f465c",
                    "tertiary": "#000000",
                    "outline-variant": "#c6c6cd",
                    "on-error-container": "#93000a",
                    "surface": "#fcf8fa",
                    "on-surface-variant": "#45464d",
                    "inverse-primary": "#bec6e0",
                    "on-tertiary-container": "#75859d",
                    "surface-container-lowest": "#ffffff",
                    "inverse-surface": "#303032",
                    "secondary": "#006a61",
                    "on-tertiary-fixed-variant": "#38485d",
                    "on-secondary-container": "#006f66",
                    "on-error": "#ffffff",
                    "on-primary-fixed": "#131b2e",
                    "secondary-fixed": "#89f5e7",
                    "on-primary-container": "#7c839b",
                    "primary-container": "#131b2e",
                    "on-surface": "#1b1b1d",
                    "secondary-container": "#86f2e4",
                    "surface-tint": "#565e74",
                    "tertiary-fixed": "#d3e4fe",
                    "background": "#fcf8fa",
                    "outline": "#76777d",
                    "primary-fixed-dim": "#bec6e0",
                    "inverse-on-surface": "#f3f0f2",
                    "surface-container-highest": "#e4e2e4",
                    "tertiary-fixed-dim": "#b7c8e1",
                    "on-secondary": "#ffffff",
                    "on-tertiary-fixed": "#0b1c30",
                    "secondary-fixed-dim": "#6bd8cb",
                    "error-container": "#ffdad6",
                    "surface-bright": "#fcf8fa",
                    "primary-fixed": "#dae2fd",
                    "on-primary": "#ffffff",
                    "on-secondary-fixed": "#00201d",
                    "soft-border": "#e1e4e8",
                    "clinical-white": "#ffffff"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "gutter": "24px",
                    "margin-mobile": "20px",
                    "unit": "4px",
                    "container-max": "1280px",
                    "section-gap": "120px",
                    "margin-desktop": "64px"
            },
            "fontFamily": {
                    "label-sm": ["Inter"],
                    "label-md": ["Inter"],
                    "display-lg-mobile": ["Plus Jakarta Sans"],
                    "headline-sm": ["Plus Jakarta Sans"],
                    "display-lg": ["Plus Jakarta Sans"],
                    "headline-md": ["Plus Jakarta Sans"],
                    "body-lg": ["Inter"],
                    "body-md": ["Inter"]
            },
            "fontSize": {
                    "label-sm": ["12px", {"lineHeight": "1.2", "fontWeight": "500"}],
                    "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.01em", "fontWeight": "600"}],
                    "display-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "display-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "headline-md": ["30px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style type="text/tailwindcss">
        .input-minimal {
            @apply w-full bg-transparent border-0 border-b border-soft-border px-0 py-3 text-on-surface focus:ring-0 focus:border-primary focus:border-b transition-colors;
        }
        .btn-primary {
            @apply inline-flex items-center justify-center bg-primary text-clinical-white font-body-md px-8 py-3 rounded transition-transform active:scale-95;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .clinical-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .clinical-card:hover {
            box-shadow: 0 10px 30px -10px rgba(19, 27, 46, 0.08);
            border-color: #006a61;
        }
        .glass-header {
            backdrop-filter: blur(8px);
            background: rgba(252, 248, 250, 0.85);
        }
        .portfolio-overlay {
            background: linear-gradient(to top, rgba(11, 28, 48, 0.9) 0%, rgba(11, 28, 48, 0.2) 60%, transparent 100%);
        }
        .nav-link-active {
            border-bottom: 2px solid #0f62fe;
            color: #0f62fe;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-md selection:bg-secondary-fixed selection:text-on-secondary-fixed">
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-outline-variant transition-all duration-300" id="navbar">
        <div class="flex justify-between items-center max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop h-16">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ setting('logo') ? asset('storage/' . setting('logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'Kare ONS Herbals') }} Logo" class="h-12 w-auto object-contain">
            </a>
            <div class="hidden md:flex items-center gap-8 h-full">
                <a class="{{ request()->routeIs('home') ? 'nav-link-active' : 'text-on-surface hover:text-primary transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('home') }}">Home</a>
                <a class="{{ request()->routeIs('shop.index') ? 'nav-link-active' : 'text-on-surface hover:text-primary transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('shop.index') }}">Shop</a>
                <div class="relative group h-full flex items-center">
                    <button class="flex items-center gap-1 text-on-surface font-medium hover:text-primary transition-colors duration-200 text-sm">
                        Categories
                        <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>
                    <div class="absolute top-full left-0 w-64 bg-white shadow-lg border border-outline-variant opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 rounded-b-lg overflow-hidden">
                        <div class="flex flex-col py-1">
                            @foreach($headerCategories ?? [] as $navCat)
                                <a class="px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container hover:text-primary transition-colors" href="{{ route('shop.index', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a class="{{ request()->routeIs('about') ? 'nav-link-active' : 'text-on-surface hover:text-primary transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('about') }}">About</a>
                <a class="{{ request()->routeIs('blog.index') ? 'nav-link-active' : 'text-on-surface hover:text-primary transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('blog.index') }}">Blog</a>
                <a class="{{ request()->routeIs('contact') ? 'nav-link-active' : 'text-on-surface hover:text-primary transition-colors' }} flex items-center h-full px-1 text-sm font-medium" href="{{ route('contact') }}">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-on-surface hover:text-primary transition">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <a href="{{ route('cart.index') }}" class="relative text-on-surface hover:text-primary transition">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    @php
                        $cartCount = 0;
                        if(Auth::check()) {
                            $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                        } else {
                            $cartCount = \App\Models\CartItem::where('session_id', Session::getId())->sum('quantity');
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-primary text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="text-on-surface hover:text-primary transition ml-2 flex items-center gap-1">
                        <span class="material-symbols-outlined">account_circle</span>
                        <span class="text-sm font-medium hidden md:inline">Account</span>
                    </a>
                @else
                    <div class="flex items-center gap-4 ml-2">
                        <a href="{{ route('login') }}" class="text-on-surface hover:text-primary transition flex items-center gap-1">
                            <span class="material-symbols-outlined">login</span>
                            <span class="text-sm font-medium hidden md:inline">Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary text-white hover:bg-on-surface-variant transition px-4 py-2 rounded-md text-sm font-medium flex items-center gap-1">
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
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-outline-variant absolute top-16 left-0 w-full shadow-lg h-[calc(100vh-64px)] overflow-y-auto">
            <div class="flex flex-col py-4 px-margin-mobile">
                <a class="{{ request()->routeIs('home') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium border-b border-surface-container" href="{{ route('home') }}">Home</a>
                <a class="{{ request()->routeIs('shop.index') ? 'nav-link-active' : 'text-on-surface' }} py-3 text-sm font-medium border-b border-surface-container" href="{{ route('shop.index') }}">Shop</a>
                
                <div class="py-3 border-b border-surface-container">
                    <p class="text-sm font-medium text-on-surface mb-2">Categories</p>
                    <div class="flex flex-col pl-4 gap-3">
                        @foreach($headerCategories ?? [] as $navCat)
                            <a class="text-sm text-on-surface-variant hover:text-primary" href="{{ route('shop.index', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a>
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
        <header class="bg-surface shadow-sm border-b border-outline-variant mt-16">
            <div class="max-w-container-max mx-auto py-6 px-margin-mobile md:px-margin-desktop">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="flex-grow pt-16">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer class="w-full bg-primary dark:bg-tertiary-container">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-section-gap grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-gutter">
<div class="col-span-1 md:col-span-1">
<a class="block mb-6" href="{{ route('home') }}">
    <img src="{{ setting('logo') ? asset('storage/' . setting('logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'Kare ONS Herbals') }} Logo" class="h-20 w-auto object-contain bg-white rounded-full p-2">
</a>
<p class="text-on-primary/80 text-label-md mb-8 leading-relaxed">
                    {!! setting('about_text', 'Setting the global benchmark for scientific Ayurveda and botanical clinical excellence since 1999.') !!}
                </p>
</div>
<div>
<h5 class="text-secondary-fixed font-label-md uppercase tracking-widest mb-6">Explore</h5>
<ul class="space-y-4">
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Contract Manufacturing</a></li>
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Bulk Extracts</a></li>
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Formulation Lab</a></li>
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">White Labeling</a></li>
</ul>
</div>
<div>
<h5 class="text-secondary-fixed font-label-md uppercase tracking-widest mb-6">Science</h5>
<ul class="space-y-4">
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Clinical Studies</a></li>
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Ingredient Sourcing</a></li>
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Quality Control</a></li>
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="#">Regulatory Support</a></li>
</ul>
</div>
<div>
<h5 class="text-secondary-fixed font-label-md uppercase tracking-widest mb-6">Company</h5>
<ul class="space-y-4">
@foreach($footerPages ?? [] as $page)
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="{{ route('page.show', $page->slug) }}">{{ $page->title }}</a></li>
@endforeach
<li class=""><a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="{{ route('about') }}">About Us</a></li>
</ul>
</div>
<div>
<h5 class="text-secondary-fixed font-label-md uppercase tracking-widest mb-6">Contact Us</h5>
<ul class="space-y-4">
@if(setting('site_email'))
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-fixed text-[20px]">mail</span>
<a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="mailto:{{ setting('site_email') }}">{{ setting('site_email') }}</a>
</li>
@endif
@if(setting('site_phone'))
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-fixed text-[20px]">call</span>
<a class="text-on-primary/80 hover:text-secondary-fixed transition-colors font-body-md" href="tel:{{ setting('site_phone') }}">{{ setting('site_phone') }}</a>
</li>
@endif
@if(setting('address'))
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-fixed text-[20px]">location_on</span>
<span class="text-on-primary/80 font-body-md">{{ setting('address') }}</span>
</li>
@endif
</ul>
<div class="flex gap-4 mt-6">
@if(setting('linkedin_url'))
<a class="w-10 h-10 rounded-full border border-on-primary/20 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed transition-colors" href="{{ setting('linkedin_url') }}" target="_blank" title="LinkedIn">
<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
</a>
@endif
@if(setting('instagram_url'))
<a class="w-10 h-10 rounded-full border border-on-primary/20 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed transition-colors" href="{{ setting('instagram_url') }}" target="_blank" title="Instagram">
<span class="material-symbols-outlined text-[20px]">photo_camera</span>
</a>
@endif
@if(setting('twitter_url'))
<a class="w-10 h-10 rounded-full border border-on-primary/20 flex items-center justify-center text-on-primary hover:bg-secondary-fixed hover:text-on-secondary-fixed transition-colors" href="{{ setting('twitter_url') }}" target="_blank" title="Twitter">
<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
</a>
@endif
</div>
</div>
</div>
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8 border-t border-on-primary/10">
<p class="text-on-primary/60 text-label-sm text-center">
                {{ setting('copyright_text', '© ' . date('Y') . ' Kare ONS Herbals. All rights reserved.') }}
            </p>
</div>
</footer>
    
    <script>
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
                        icon.classList.add('text-primary');
                        icon.classList.remove('text-on-surface-variant');
                        if (icon.parentElement.tagName === 'BUTTON') {
                            icon.parentElement.classList.add('border-primary', 'bg-primary/10');
                            icon.parentElement.classList.remove('border-soft-border');
                        }
                    });
                } else if (data.status === 'removed') {
                    document.querySelectorAll(`.wishlist-icon-${productId}`).forEach(icon => {
                        icon.style.fontVariationSettings = "'FILL' 0";
                        icon.classList.remove('text-primary');
                        icon.classList.add('text-on-surface-variant');
                        if (icon.parentElement.tagName === 'BUTTON') {
                            icon.parentElement.classList.remove('border-primary', 'bg-primary/10');
                            icon.parentElement.classList.add('border-soft-border');
                        }
                    });
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
