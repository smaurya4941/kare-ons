@extends('layouts.app')

@section('content')
<style>
    .section-eyebrow {
        letter-spacing: 0.18em;
    }
    /* Palette-aware product card lift */
    .card-lift {
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }
    .card-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px -20px rgba(30, 58, 51, 0.35);
        border-color: rgba(201, 164, 82, 0.5);
    }
</style>

@php
    // ---------------------------------------------------------------------
    // Hero imagery: prefer an admin "hero" banner (with a mobile variant),
    // fall back to the hero background setting, then a bundled local asset.
    // ---------------------------------------------------------------------
    $heroBanner  = $banners->firstWhere('type', 'hero');
    $heroDesktop = $heroBanner
        ? image_url($heroBanner->desktop_image)
        : (setting('home_hero_bg') ? image_url(setting('home_hero_bg')) : asset('images/home/hero.jpg'));
    $heroMobile  = ($heroBanner && $heroBanner->mobile_image)
        ? image_url($heroBanner->mobile_image)
        : $heroDesktop;
@endphp

{{-- Reusable star-rating display for product cards --}}
@php
    if (! function_exists('kareon_stars')) {
        function kareon_stars($avg) {
            $avg = (float) $avg;
            $out = [];
            for ($i = 1; $i <= 5; $i++) {
                if ($avg >= $i)            $out[] = 'star';        // full
                elseif ($avg >= $i - 0.5)  $out[] = 'star_half';   // half
                else                       $out[] = 'star_outline';// empty
            }
            return $out;
        }
    }
@endphp

<!-- Hero Section (full-width banner) -->
<section class="relative w-full overflow-hidden bg-brand-forest min-h-screen flex items-center">
    <!-- Full-bleed, art-directed background image -->
    <div class="absolute inset-0 z-0">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ $heroMobile }}">
            <img class="w-full h-full object-cover" src="{{ $heroDesktop }}" alt="{{ setting('site_name', 'Kareon') }} premium Ayurvedic products" fetchpriority="high" decoding="async"/>
        </picture>
        <div class="absolute inset-0 bg-gradient-to-r from-brand-forest via-brand-forest/80 to-brand-forest/30"></div>
    </div>

    <!-- Overlaid content -->
    <div class="relative z-10 w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12 lg:py-16">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-brand-gold text-brand-forest font-label-md text-label-sm uppercase section-eyebrow px-3.5 py-1 rounded-full mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[15px]" style="font-variation-settings:'FILL' 1;" aria-hidden="true">spa</span>
                {{ setting('home_hero_badge', 'Since 1999') }}
            </div>
            <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-brand-cream mb-4 leading-tight">
                {!! setting('home_hero_title', 'Scientific Ayurveda for <br/><span class="text-brand-gold">Modern Wellness</span>') !!}
            </h1>
            <p class="font-body-lg text-body-md md:text-body-lg text-brand-cream/85 mb-6 max-w-xl leading-relaxed">
                {!! setting('home_hero_subtitle', 'Harmonizing elemental nature with diagnostic precision. We bridge 5,000 years of Vedic wisdom with contemporary clinical validation to restore your inherent vitality.') !!}
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ setting('home_cta_link', route('shop.index')) }}" class="bg-brand-gold text-brand-forest font-label-md text-label-md px-6 py-2.5 rounded-full transition-all hover:bg-brand-gold-dark hover:shadow-lg hover:scale-105 active:scale-95">
                    {{ setting('home_cta_text', 'Shop Now') }}
                </a>
                <a href="{{ route('about') }}" class="border border-brand-cream/60 text-brand-cream font-label-md text-label-md px-6 py-2.5 rounded-full transition-all hover:bg-brand-cream hover:text-brand-forest active:scale-95">
                    Learn Our Process
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Standard of Excellence (Certifications) — trust signals directly under the hero -->
<section class="py-6 bg-brand-cream border-b border-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center">
        <p class="font-label-md text-label-sm text-brand-sage-dark uppercase section-eyebrow mb-4">Standard of Excellence Certifications</p>
        <div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-4">
            @php
                $certs = [
                    ['icon' => 'verified', 'label' => 'GMP Certified'],
                    ['icon' => 'workspace_premium', 'label' => 'ISO 9001:2015'],
                    ['icon' => 'eco', 'label' => 'AYUSH Premium'],
                    ['icon' => 'public', 'label' => 'PAN INDIA'],
                    ['icon' => 'inventory_2', 'label' => 'FDA Compliant'],
                ];
            @endphp
            @foreach($certs as $cert)
                <div class="flex items-center gap-2.5 group">
                    <span class="material-symbols-outlined text-[28px] text-brand-forest transition-transform group-hover:scale-110 group-hover:text-brand-gold-dark" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">{{ $cert['icon'] }}</span>
                    <span class="font-label-md text-label-md text-brand-forest">{{ $cert['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Category Grid -->
<section class="py-10 md:py-14 bg-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Curated Care</span>
                <h2 class="font-headline-md text-display-lg-mobile text-brand-forest mb-1">Explore Solutions</h2>
                <p class="font-body-md text-body-md text-brand-forest/70">Targeted botanical care for your unique physiological needs.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-brand-gold-dark font-label-md text-label-md flex items-center gap-1 hover:gap-2 transition-all shrink-0">
                View All Categories <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
        @php
            // Bundled placeholder imagery, cycled through for categories without an image.
            $categoryPlaceholders = ['images/home/women.jpg', 'images/home/skin.jpg', 'images/home/hair.jpg', 'images/home/health.jpg'];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter">
            @forelse($homepageCategories as $i => $category)
                @php
                    $categoryImage = $category->banner_image ?? $category->image;
                    $categorySrc   = $categoryImage
                        ? image_url($categoryImage)
                        : asset($categoryPlaceholders[$i % count($categoryPlaceholders)]);
                @endphp
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="group relative block overflow-hidden rounded-xl aspect-[4/5] bg-brand-forest shadow-sm hover:shadow-xl transition-all">
                    <img class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:scale-105 group-hover:opacity-100 transition-all duration-500" src="{{ $categorySrc }}" alt="{{ $category->name }}" loading="lazy" decoding="async"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-forest via-brand-forest/40 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-4 md:p-5">
                        <h3 class="font-headline-sm text-lg md:text-headline-sm text-brand-cream mb-1 leading-tight">{{ $category->name }}</h3>
                        <span class="inline-flex items-center gap-1 text-brand-gold font-label-md text-label-sm uppercase section-eyebrow opacity-0 -translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition-all">
                            Explore <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-brand-forest/60">
                    <span class="material-symbols-outlined text-5xl mb-2 block" aria-hidden="true">category</span>
                    <p class="font-body-md text-body-md">Categories are being curated. Please check back soon.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-10 md:py-14 bg-white border-b border-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Handpicked</span>
                <h2 class="font-headline-md text-display-lg-mobile text-brand-forest">Featured Products</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-brand-gold-dark font-label-md text-label-md flex items-center gap-1 hover:gap-2 transition-all shrink-0">
                View All <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter">
            @foreach($featuredProducts as $product)
                @php
                    $inWishlist = in_array($product->id, $wishlistIds ?? []);
                    $avg        = round((float) ($product->reviews_avg_rating ?? 0), 1);
                    $count      = (int) ($product->reviews_count ?? 0);
                @endphp
                <div class="group bg-white border border-brand-beige rounded-xl overflow-hidden card-lift flex flex-col h-full relative shadow-sm">
                    @if($product->sale_price)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-2 py-1 text-error text-xs font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                        </div>
                    @endif

                    <!-- Wishlist -->
                    <div class="absolute top-3 right-3 z-20">
                        @auth
                            <button type="button" onclick="toggleWishlist({{ $product->id }})" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}" aria-label="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <span class="wishlist-icon-{{ $product->id }} material-symbols-outlined text-[20px] {{ $inWishlist ? 'text-brand-gold-dark' : 'text-brand-forest/50 hover:text-brand-gold-dark' }}" style="font-variation-settings: 'FILL' {{ $inWishlist ? '1' : '0' }};">favorite</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors text-brand-forest/50 hover:text-brand-gold-dark" title="Log in to save" aria-label="Log in to save to wishlist">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </a>
                        @endauth
                    </div>

                    <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square bg-brand-cream overflow-hidden relative">
                        @if($product->main_image)
                            <img src="{{ image_url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-brand-beige text-brand-sage">
                                <span class="material-symbols-outlined text-5xl" aria-hidden="true">image</span>
                            </div>
                        @endif
                    </a>

                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-[11px] font-medium text-brand-sage-dark mb-1 tracking-wider uppercase">{{ $product->category->name ?? 'Ayurvedic' }}</div>
                        <a href="{{ route('product.show', $product->slug) }}" class="font-headline-sm text-base font-semibold text-brand-forest leading-tight hover:text-brand-gold-dark transition-colors line-clamp-1 mb-1.5">
                            {{ $product->name }}
                        </a>

                        <!-- Rating (real, from approved reviews) -->
                        <div class="flex items-center gap-1 mb-2 h-5">
                            @if($count > 0)
                                @foreach(kareon_stars($avg) as $starIcon)
                                    <span class="material-symbols-outlined text-[15px]" style="color:#c9a452;font-variation-settings: 'FILL' {{ $starIcon === 'star_outline' ? '0' : '1' }};" aria-hidden="true">{{ $starIcon === 'star_outline' ? 'star' : $starIcon }}</span>
                                @endforeach
                                <span class="text-xs text-brand-forest/60 ml-1">{{ number_format($avg, 1) }} ({{ $count }})</span>
                            @else
                                <span class="text-xs text-brand-forest/40">No reviews yet</span>
                            @endif
                        </div>

                        <div class="mt-auto pt-3 flex items-center justify-between border-t border-brand-beige group-hover:border-brand-gold/30 transition-colors">
                            <div class="flex flex-col">
                                @if($product->sale_price)
                                    <span class="text-xs text-brand-forest/50 line-through">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->sale_price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.add') }}" method="POST" class="js-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-10 h-10 rounded-full bg-brand-forest text-brand-cream flex items-center justify-center hover:bg-brand-gold hover:text-brand-forest transition-colors active:scale-95 shadow-sm" title="Add to Cart" aria-label="Add {{ $product->name }} to cart">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">add_shopping_cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Best Sellers -->
@if(false && $bestSellers->count() > 0)
<section class="py-10 md:py-14 bg-brand-cream">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Most Loved</span>
                <h2 class="font-headline-md text-display-lg-mobile text-brand-forest">Best Sellers</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-brand-gold-dark font-label-md text-label-md flex items-center gap-1 hover:gap-2 transition-all shrink-0">
                View All <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter">
            @foreach($bestSellers as $product)
                @php
                    $inWishlist = in_array($product->id, $wishlistIds ?? []);
                    $avg        = round((float) ($product->reviews_avg_rating ?? 0), 1);
                    $count      = (int) ($product->reviews_count ?? 0);
                @endphp
                <div class="group bg-white border border-brand-beige rounded-xl overflow-hidden card-lift flex flex-col h-full relative shadow-sm">
                    @if($product->sale_price)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-2 py-1 text-error text-xs font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                        </div>
                    @endif

                    <!-- Wishlist -->
                    <div class="absolute top-3 right-3 z-20">
                        @auth
                            <button type="button" onclick="toggleWishlist({{ $product->id }})" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}" aria-label="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <span class="wishlist-icon-{{ $product->id }} material-symbols-outlined text-[20px] {{ $inWishlist ? 'text-brand-gold-dark' : 'text-brand-forest/50 hover:text-brand-gold-dark' }}" style="font-variation-settings: 'FILL' {{ $inWishlist ? '1' : '0' }};">favorite</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors text-brand-forest/50 hover:text-brand-gold-dark" title="Log in to save" aria-label="Log in to save to wishlist">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </a>
                        @endauth
                    </div>

                    <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square bg-brand-cream overflow-hidden relative">
                        @if($product->main_image)
                            <img src="{{ image_url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-brand-beige text-brand-sage">
                                <span class="material-symbols-outlined text-5xl" aria-hidden="true">image</span>
                            </div>
                        @endif
                    </a>

                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-[11px] font-medium text-brand-sage-dark mb-1 tracking-wider uppercase">{{ $product->category->name ?? 'Ayurvedic' }}</div>
                        <a href="{{ route('product.show', $product->slug) }}" class="font-headline-sm text-base font-semibold text-brand-forest leading-tight hover:text-brand-gold-dark transition-colors line-clamp-1 mb-1.5">
                            {{ $product->name }}
                        </a>

                        <!-- Rating (real, from approved reviews) -->
                        <div class="flex items-center gap-1 mb-2 h-5">
                            @if($count > 0)
                                @foreach(kareon_stars($avg) as $starIcon)
                                    <span class="material-symbols-outlined text-[15px]" style="color:#c9a452;font-variation-settings: 'FILL' {{ $starIcon === 'star_outline' ? '0' : '1' }};" aria-hidden="true">{{ $starIcon === 'star_outline' ? 'star' : $starIcon }}</span>
                                @endforeach
                                <span class="text-xs text-brand-forest/60 ml-1">{{ number_format($avg, 1) }} ({{ $count }})</span>
                            @else
                                <span class="text-xs text-brand-forest/40">No reviews yet</span>
                            @endif
                        </div>

                        <div class="mt-auto pt-3 flex items-center justify-between border-t border-brand-beige group-hover:border-brand-gold/30 transition-colors">
                            <div class="flex flex-col">
                                @if($product->sale_price)
                                    <span class="text-xs text-brand-forest/50 line-through">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->sale_price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.add') }}" method="POST" class="js-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-10 h-10 rounded-full bg-brand-forest text-brand-cream flex items-center justify-center hover:bg-brand-gold hover:text-brand-forest transition-colors active:scale-95 shadow-sm" title="Add to Cart" aria-label="Add {{ $product->name }} to cart">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">add_shopping_cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Trending Products -->
@if($trendingProducts->count() > 0)
<section class="py-10 md:py-14 bg-white border-t border-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Popular Now</span>
                <h2 class="font-headline-md text-display-lg-mobile text-brand-forest">Trending Products</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-brand-gold-dark font-label-md text-label-md flex items-center gap-1 hover:gap-2 transition-all shrink-0">
                View All <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter">
            @foreach($trendingProducts as $product)
                @php
                    $inWishlist = in_array($product->id, $wishlistIds ?? []);
                    $avg        = round((float) ($product->reviews_avg_rating ?? 0), 1);
                    $count      = (int) ($product->reviews_count ?? 0);
                @endphp
                <div class="group bg-white border border-brand-beige rounded-xl overflow-hidden card-lift flex flex-col h-full relative shadow-sm">
                    @if($product->sale_price)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-2 py-1 text-error text-xs font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                        </div>
                    @endif

                    <!-- Wishlist -->
                    <div class="absolute top-3 right-3 z-20">
                        @auth
                            <button type="button" onclick="toggleWishlist({{ $product->id }})" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}" aria-label="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <span class="wishlist-icon-{{ $product->id }} material-symbols-outlined text-[20px] {{ $inWishlist ? 'text-brand-gold-dark' : 'text-brand-forest/50 hover:text-brand-gold-dark' }}" style="font-variation-settings: 'FILL' {{ $inWishlist ? '1' : '0' }};">favorite</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors text-brand-forest/50 hover:text-brand-gold-dark" title="Log in to save" aria-label="Log in to save to wishlist">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </a>
                        @endauth
                    </div>

                    <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square bg-brand-cream overflow-hidden relative">
                        @if($product->main_image)
                            <img src="{{ image_url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-brand-beige text-brand-sage">
                                <span class="material-symbols-outlined text-5xl" aria-hidden="true">image</span>
                            </div>
                        @endif
                    </a>

                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-[11px] font-medium text-brand-sage-dark mb-1 tracking-wider uppercase">{{ $product->category->name ?? 'Ayurvedic' }}</div>
                        <a href="{{ route('product.show', $product->slug) }}" class="font-headline-sm text-base font-semibold text-brand-forest leading-tight hover:text-brand-gold-dark transition-colors line-clamp-1 mb-1.5">
                            {{ $product->name }}
                        </a>

                        <!-- Rating (real, from approved reviews) -->
                        <div class="flex items-center gap-1 mb-2 h-5">
                            @if($count > 0)
                                @foreach(kareon_stars($avg) as $starIcon)
                                    <span class="material-symbols-outlined text-[15px]" style="color:#c9a452;font-variation-settings: 'FILL' {{ $starIcon === 'star_outline' ? '0' : '1' }};" aria-hidden="true">{{ $starIcon === 'star_outline' ? 'star' : $starIcon }}</span>
                                @endforeach
                                <span class="text-xs text-brand-forest/60 ml-1">{{ number_format($avg, 1) }} ({{ $count }})</span>
                            @else
                                <span class="text-xs text-brand-forest/40">No reviews yet</span>
                            @endif
                        </div>

                        <div class="mt-auto pt-3 flex items-center justify-between border-t border-brand-beige group-hover:border-brand-gold/30 transition-colors">
                            <div class="flex flex-col">
                                @if($product->sale_price)
                                    <span class="text-xs text-brand-forest/50 line-through">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->sale_price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.add') }}" method="POST" class="js-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-10 h-10 rounded-full bg-brand-forest text-brand-cream flex items-center justify-center hover:bg-brand-gold hover:text-brand-forest transition-colors active:scale-95 shadow-sm" title="Add to Cart" aria-label="Add {{ $product->name }} to cart">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">add_shopping_cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- New Arrivals -->
@if($newArrivals->count() > 0)
<section class="py-10 md:py-14 bg-brand-cream border-t border-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Just In</span>
                <h2 class="font-headline-md text-display-lg-mobile text-brand-forest">New Arrivals</h2>
            </div>
            <a href="{{ route('shop.index', ['sort' => 'latest']) }}" class="text-brand-gold-dark font-label-md text-label-md flex items-center gap-1 hover:gap-2 transition-all shrink-0">
                View All <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter">
            @foreach($newArrivals as $product)
                @php
                    $inWishlist = in_array($product->id, $wishlistIds ?? []);
                    $avg        = round((float) ($product->reviews_avg_rating ?? 0), 1);
                    $count      = (int) ($product->reviews_count ?? 0);
                @endphp
                <div class="group bg-white border border-brand-beige rounded-xl overflow-hidden card-lift flex flex-col h-full relative shadow-sm">
                    @if($product->sale_price)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-2 py-1 text-error text-xs font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                        </div>
                    @endif

                    <!-- Wishlist -->
                    <div class="absolute top-3 right-3 z-20">
                        @auth
                            <button type="button" onclick="toggleWishlist({{ $product->id }})" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}" aria-label="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <span class="wishlist-icon-{{ $product->id }} material-symbols-outlined text-[20px] {{ $inWishlist ? 'text-brand-gold-dark' : 'text-brand-forest/50 hover:text-brand-gold-dark' }}" style="font-variation-settings: 'FILL' {{ $inWishlist ? '1' : '0' }};">favorite</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors text-brand-forest/50 hover:text-brand-gold-dark" title="Log in to save" aria-label="Log in to save to wishlist">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </a>
                        @endauth
                    </div>

                    <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square bg-brand-cream overflow-hidden relative">
                        @if($product->main_image)
                            <img src="{{ image_url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-brand-beige text-brand-sage">
                                <span class="material-symbols-outlined text-5xl" aria-hidden="true">image</span>
                            </div>
                        @endif
                    </a>

                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-[11px] font-medium text-brand-sage-dark mb-1 tracking-wider uppercase">{{ $product->category->name ?? 'Ayurvedic' }}</div>
                        <a href="{{ route('product.show', $product->slug) }}" class="font-headline-sm text-base font-semibold text-brand-forest leading-tight hover:text-brand-gold-dark transition-colors line-clamp-1 mb-1.5">
                            {{ $product->name }}
                        </a>

                        <!-- Rating (real, from approved reviews) -->
                        <div class="flex items-center gap-1 mb-2 h-5">
                            @if($count > 0)
                                @foreach(kareon_stars($avg) as $starIcon)
                                    <span class="material-symbols-outlined text-[15px]" style="color:#c9a452;font-variation-settings: 'FILL' {{ $starIcon === 'star_outline' ? '0' : '1' }};" aria-hidden="true">{{ $starIcon === 'star_outline' ? 'star' : $starIcon }}</span>
                                @endforeach
                                <span class="text-xs text-brand-forest/60 ml-1">{{ number_format($avg, 1) }} ({{ $count }})</span>
                            @else
                                <span class="text-xs text-brand-forest/40">No reviews yet</span>
                            @endif
                        </div>

                        <div class="mt-auto pt-3 flex items-center justify-between border-t border-brand-beige group-hover:border-brand-gold/30 transition-colors">
                            <div class="flex flex-col">
                                @if($product->sale_price)
                                    <span class="text-xs text-brand-forest/50 line-through">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->sale_price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-brand-forest">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.add') }}" method="POST" class="js-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-10 h-10 rounded-full bg-brand-forest text-brand-cream flex items-center justify-center hover:bg-brand-gold hover:text-brand-forest transition-colors active:scale-95 shadow-sm" title="Add to Cart" aria-label="Add {{ $product->name }} to cart">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">add_shopping_cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Ingredient Spotlight -->
<section class="w-full overflow-hidden">
    <div class="w-full h-[300px] md:h-[450px] relative group">
        @if(setting('home_ingredient_spotlight_bg'))
            <img alt="{{ setting('home_ingredient_spotlight_title', 'Ingredient Spotlight') }}" class="w-full h-full object-cover" src="{{ image_url(setting('home_ingredient_spotlight_bg')) }}">
        @else
            <img alt="Kareons Herbal premium ingredients" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZjahQrYHUTpE0VLHCkc3BR6D0q2yZqbObDX0-kWSmawbQ9XL4GFEVWlq1wax2Dd13oggRZT4yaaUQySQ4LtUI8bWPBGDhG793oof2XIRcAsRDnxCMF_LuYpDoQ6wtuBE6jPlORNeO3F5BkiuJGdqScb_-LtTU73rjPtPuQvfsVJmmOtb0sgcb-pI89tZxVSnMTghu6mUzl2KY52vmE7gVmYAkQnASFcjHw9a4xQ2zQ2EBkT9oXw674A">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-brand-forest/60 to-transparent flex flex-col justify-end p-8 md:p-16">
            <div class="max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop">
                <span class="font-label-md text-label-sm text-brand-gold uppercase tracking-widest block mb-2 section-eyebrow">Ingredient Spotlight</span>
                <h2 class="font-display-lg text-display-lg-mobile md:text-display-lg text-brand-cream mb-4">
                    {{ setting('home_ingredient_spotlight_title', 'The Essence of Vedic Wisdom') }}
                </h2>
                <div class="flex flex-wrap gap-4 md:gap-8">
                    @php
                        $ingredientsStr = setting('home_ingredient_spotlight_ingredients', 'Neem,Tulsi,Ashwagandha,Amla');
                        $ingredients = array_filter(array_map('trim', explode(',', $ingredientsStr)));
                    @endphp
                    @foreach($ingredients as $ingredient)
                    <div class="flex items-center gap-2 text-brand-cream/90">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                        <span class="font-label-md text-label-sm uppercase tracking-wider">{{ $ingredient }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Expert Quote Section (driven by settings) -->
<section class="bg-brand-forest text-brand-cream relative overflow-hidden">
    <div class="relative z-10 flex flex-col md:flex-row w-full h-full md:min-h-[400px]">
        <div class="w-full md:w-1/3 bg-brand-forest-dark flex items-center justify-center overflow-hidden relative min-h-[300px]">
            @if(setting('home_expert_image'))
                <img src="{{ image_url(setting('home_expert_image')) }}" alt="{{ setting('home_expert_name', 'Expert') }}" class="w-full h-full object-cover">
            @else
                <img src="https://images.unsplash.com/photo-1594824436998-df40959e1927?q=80&w=1000&auto=format&fit=crop" alt="Expert Ayurvedic Doctor" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-brand-forest/80 md:to-brand-forest"></div>
        </div>
        <div class="w-full md:w-2/3 flex flex-col justify-center px-margin-mobile md:px-16 py-12 md:py-16 text-left bg-brand-forest relative">
            <div class="pointer-events-none absolute -top-16 -right-10 w-72 h-72 rounded-full bg-brand-gold/10 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-20 left-10 w-72 h-72 rounded-full bg-brand-sage/10 blur-3xl" aria-hidden="true"></div>
            
            <span class="material-symbols-outlined text-[48px] mb-4 text-brand-gold opacity-50 relative z-10" style="font-variation-settings:'FILL' 1;" aria-hidden="true">format_quote</span>
            <h3 class="font-display-lg text-headline-sm md:text-headline-md italic mb-8 leading-relaxed relative z-10 max-w-3xl">
                "{{ setting('home_expert_quote', 'True healing occurs when we harmonize the elemental wisdom of nature with the diagnostic precision of science.') }}"
            </h3>
            
            <div class="border-t border-brand-gold/20 pt-6 mt-2 relative z-10">
                <p class="font-headline-sm text-headline-sm text-brand-cream">{{ setting('home_expert_name', 'Dr. Rajni Dubey') }}</p>
                <p class="font-body-md text-body-md text-brand-gold uppercase tracking-widest text-xs mt-1">{{ setting('home_expert_designation', 'Expert Ayurvedic Vaidya (B.A.M.S)') }}</p>
                @if(setting('home_expert_description'))
                    <p class="font-label-md text-label-md text-brand-cream/70 mt-2">{{ setting('home_expert_description') }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Ethos Section -->
<section class="py-10 md:py-14 bg-brand-cream">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center max-w-2xl mx-auto mb-10 md:mb-12">
            <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">What Drives Us</span>
            <h2 class="font-display-lg text-display-lg-mobile md:text-display-lg text-brand-forest mb-3">Our Ethos</h2>
            <p class="font-body-md text-body-md text-brand-forest/70">The core principles that guide our clinical excellence and pharmaceutical integrity.</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter">
            @php
                $ethos = [
                    ['icon' => 'shield_person', 'title' => 'Integrity', 'text' => 'Absolute transparency in sourcing and manufacturing for unwavering trust.'],
                    ['icon' => 'groups', 'title' => 'Teamwork', 'text' => 'Collaborative intelligence of scientists, vaidyas, and process engineers.'],
                    ['icon' => 'eco', 'title' => 'Pure Ayurveda', 'text' => 'Upholding the sanctity of ancient recipes with pharmaceutical precision.'],
                    ['icon' => 'psychology_alt', 'title' => 'Innovation', 'text' => 'Continuous R&D to improve bioavailability and therapeutic delivery.'],
                ];
            @endphp
            @foreach($ethos as $item)
                <div class="text-center p-4 md:p-5 rounded-xl bg-white border border-brand-beige hover:border-brand-gold/40 hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-brand-sage/15 rounded-full flex items-center justify-center mx-auto mb-3 border border-brand-sage/30">
                        <span class="material-symbols-outlined text-brand-forest text-[28px]" style="font-variation-settings:'FILL' 1;" aria-hidden="true">{{ $item['icon'] }}</span>
                    </div>
                    <h4 class="font-headline-sm text-lg md:text-headline-sm text-brand-forest mb-2">{{ $item['title'] }}</h4>
                    <p class="font-body-md text-label-md text-brand-forest/70 leading-relaxed">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials -->
@if($testimonials->count() > 0)
<section class="py-10 md:py-14 bg-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-8 md:mb-10">
            <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Real Results</span>
            <h2 class="font-display-lg text-display-lg-mobile text-brand-forest">What Our Patients Say</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-gutter">
            @foreach($testimonials as $testimonial)
                <div class="bg-white p-5 rounded-xl shadow-sm border border-brand-beige flex flex-col justify-between hover:shadow-md hover:border-brand-gold/40 transition-all">
                    <div>
                        <div class="flex mb-3" style="color:#c9a452;" aria-label="{{ $testimonial->rating }} out of 5 stars">
                            @for($i = 0; $i < $testimonial->rating; $i++)
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">star</span>
                            @endfor
                        </div>
                        <p class="text-brand-forest/80 italic mb-6 text-body-md leading-relaxed">"{{ $testimonial->content }}"</p>
                    </div>
                    <div>
                        <p class="font-bold text-brand-forest">{{ $testimonial->name }}</p>
                        @if($testimonial->designation)
                            <p class="text-sm text-brand-gold-dark">{{ $testimonial->designation }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Blogs -->
@if($blogs->count() > 0)
<section class="py-10 md:py-14 bg-white border-t border-brand-beige">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-label-sm font-bold text-brand-gold-dark uppercase section-eyebrow mb-2 block">Ayurvedic Wisdom</span>
                <h2 class="font-headline-md text-display-lg-mobile text-brand-forest">Latest Articles</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-brand-gold-dark font-label-md text-label-md flex items-center gap-1 hover:gap-2 transition-all shrink-0">
                Read All <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            @foreach($blogs as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}" class="group block bg-brand-cream p-4 rounded-xl border border-brand-beige hover:border-brand-gold/40 hover:shadow-md transition-all">
                    <div class="overflow-hidden rounded-lg bg-brand-beige aspect-[4/3] mb-4">
                        @if($blog->featured_image)
                            <img src="{{ image_url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-brand-sage/20 text-brand-sage-dark">
                                <span class="material-symbols-outlined text-4xl">article</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-label-sm text-brand-gold-dark font-bold uppercase mb-2 block">{{ $blog->published_at->format('M d, Y') }}</span>
                        <h3 class="font-headline-sm text-lg md:text-xl text-brand-forest group-hover:text-brand-gold-dark transition-colors line-clamp-2 mb-2">
                            {{ $blog->title }}
                        </h3>
                        <p class="font-body-md text-brand-forest/70 line-clamp-2">
                            {{ $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content), 120) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
