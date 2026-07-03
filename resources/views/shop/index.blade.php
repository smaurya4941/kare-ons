@extends('layouts.app')

@section('title', 'Shop Herbal Products - Kare Ons Herbal')

@section('content')
@php
    if (! function_exists('kareon_stars')) {
        function kareon_stars($avg) {
            $avg = (float) $avg;
            $out = [];
            for ($i = 1; $i <= 5; $i++) {
                if ($avg >= $i)            $out[] = 'star';
                elseif ($avg >= $i - 0.5)  $out[] = 'star_half';
                else                       $out[] = 'star_outline';
            }
            return $out;
        }
    }
@endphp

<!-- Main Content -->
<main class="flex-grow w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-6 md:py-8" x-data="{ mobileFiltersOpen: false }">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg font-bold text-herbal-deep mb-2">
            @if(request('search'))
                Search results for "{{ request('search') }}"
            @elseif(request('category'))
                {{ $categories->firstWhere('slug', request('category'))?->name ?? 'Products' }}
            @else
                Shop All Products
            @endif
        </h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
            @if(request('search'))
                Explore our Ayurvedic formulations matching your search query.
            @elseif(request('category'))
                Discover pure, potent Ayurvedic remedies crafted for {{ strtolower($categories->firstWhere('slug', request('category'))?->name ?? 'holistic wellness') }}.
            @else
                Natural Ayurvedic formulations for complete healthcare support. Crafted with traditional wisdom and modern precision.
            @endif
        </p>
    </div>

    <!-- Mobile filter dialog -->
    <div class="relative z-40 lg:hidden" role="dialog" aria-modal="true" x-show="mobileFiltersOpen" x-cloak>
        <div class="fixed inset-0 bg-black/25 transition-opacity" x-show="mobileFiltersOpen" x-transition.opacity @click="mobileFiltersOpen = false"></div>
        <div class="fixed inset-0 z-40 flex" x-show="mobileFiltersOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="relative flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-base font-semibold text-on-surface">Filters</h2>
                    <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-on-surface-variant" @click="mobileFiltersOpen = false">
                        <span class="sr-only">Close menu</span>
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <!-- Mobile Filters Form -->
                <form class="mt-3 border-t border-soft-border" action="{{ route('shop.index') }}" method="GET">
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    <div class="px-4 py-4 border-b border-soft-border">
                        <span class="font-semibold text-sm text-on-surface">Search</span>
                        <div class="pt-3 relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full border border-soft-border rounded py-2 pl-10 pr-3 text-sm focus:border-herbal-accent focus:ring-0">
                        </div>
                    </div>

                    <div class="px-4 py-4 border-b border-soft-border">
                        <span class="font-semibold text-sm text-on-surface">Categories</span>
                        <div class="pt-3 space-y-3">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input name="category" value="" type="radio" {{ request('category') == '' ? 'checked' : '' }} class="form-radio h-4 w-4 text-herbal-accent focus:ring-herbal-accent transition-colors">
                                <span class="font-body-md text-sm text-on-surface-variant group-hover:text-herbal-accent transition-colors">All Products</span>
                            </label>
                            @foreach($categories as $category)
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input name="category" value="{{ $category->slug }}" type="radio" {{ request('category') == $category->slug ? 'checked' : '' }} class="form-radio h-4 w-4 text-herbal-accent focus:ring-herbal-accent transition-colors">
                                <span class="font-body-md text-sm text-on-surface-variant group-hover:text-herbal-accent transition-colors">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="px-4 py-4">
                        <button type="submit" class="w-full bg-herbal-deep text-white py-2 rounded font-medium text-sm">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Filters (Desktop) -->
        <aside class="hidden lg:block w-full lg:w-64 flex-shrink-0">
            <div class="sticky top-20 space-y-5 bg-white p-5 rounded-lg border border-soft-border shadow-sm">
                <form action="{{ route('shop.index') }}" method="GET" id="desktop-filter-form" class="space-y-5">
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    <!-- Search -->
                    <div>
                        <h3 class="font-label-md text-xs font-semibold uppercase tracking-wider text-on-surface mb-3">Search Products</h3>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Kare Ons..." class="w-full border border-soft-border rounded py-2 pl-10 pr-3 text-sm focus:border-herbal-accent focus:ring-0">
                        </div>
                    </div>
                    <hr class="border-soft-border"/>

                    <!-- Categories -->
                    <div>
                        <h3 class="font-label-md text-xs font-semibold uppercase tracking-wider text-on-surface mb-3">Categories</h3>
                        <div class="space-y-2.5">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="category" value="" onchange="document.getElementById('desktop-filter-form').submit()" {{ request('category') == '' ? 'checked' : '' }} class="form-radio h-4 w-4 text-herbal-accent focus:ring-herbal-accent transition-colors">
                                <span class="font-body-md text-sm {{ request('category') == '' ? 'text-herbal-accent font-medium' : 'text-on-surface-variant group-hover:text-herbal-accent' }} transition-colors">All Products</span>
                            </label>
                            @foreach($categories as $category)
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $category->slug }}" onchange="document.getElementById('desktop-filter-form').submit()" {{ request('category') == $category->slug ? 'checked' : '' }} class="form-radio h-4 w-4 text-herbal-accent focus:ring-herbal-accent transition-colors">
                                <span class="font-body-md text-sm {{ request('category') == $category->slug ? 'text-herbal-accent font-medium' : 'text-on-surface-variant group-hover:text-herbal-accent' }} transition-colors">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <hr class="border-soft-border"/>

                    <!-- Price -->
                    <div>
                        <h3 class="font-label-md text-xs font-semibold uppercase tracking-wider text-on-surface mb-3">Price Range</h3>
                        <div class="space-y-3">
                            <div class="flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full border border-soft-border rounded py-2 px-3 text-sm focus:border-herbal-accent focus:ring-0">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full border border-soft-border rounded py-2 px-3 text-sm focus:border-herbal-accent focus:ring-0">
                            </div>
                            <button type="submit" class="w-full py-2 bg-surface-container hover:bg-surface-container-high transition-colors text-sm font-medium rounded text-on-surface border border-soft-border">Apply Price Filter</button>
                        </div>
                    </div>
                    <hr class="border-soft-border"/>

                    <!-- Certification -->
                    <div>
                        <h3 class="font-label-md text-xs font-semibold uppercase tracking-wider text-on-surface mb-3">Certifications</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2.5 py-1 bg-herbal-light text-herbal-accent rounded-full text-xs font-medium border border-herbal-accent/20">GMP Certified</span>
                            <span class="px-2.5 py-1 bg-herbal-light text-herbal-accent rounded-full text-xs font-medium border border-herbal-accent/20">Ayush Certified</span>
                            <span class="px-2.5 py-1 bg-surface-container text-on-surface-variant rounded-full text-xs font-medium border border-soft-border">100% Vegan</span>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1 w-full">
            <!-- Sort & Controls -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5 pb-3 border-b border-soft-border">
                <span class="font-body-md text-sm text-on-surface-variant">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </span>

                <div class="flex items-center gap-4">
                    <button type="button" class="lg:hidden flex items-center gap-1 text-herbal-accent font-medium text-sm" @click="mobileFiltersOpen = true">
                        <span class="material-symbols-outlined text-[20px]">filter_list</span>
                        Filters
                    </button>

                    <form action="{{ route('shop.index') }}" method="GET" class="flex items-center space-x-2" id="sort-form">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                        @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif

                        <label class="font-body-md text-sm text-on-surface-variant hidden sm:block" for="sort">Sort by:</label>
                        <select name="sort" id="sort" onchange="document.getElementById('sort-form').submit()" class="form-select bg-transparent border-none text-herbal-accent font-label-md text-sm font-medium focus:ring-0 cursor-pointer pr-8">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-16 bg-white border border-soft-border rounded-xl border-dashed">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-3">inventory_2</span>
                    <h3 class="text-lg font-semibold text-on-surface">No products found</h3>
                    <p class="text-on-surface-variant mt-2 max-w-md mx-auto text-sm">We couldn't find any products matching your current filters. Try adjusting your search criteria.</p>
                    <a href="{{ route('shop.index') }}" class="mt-4 inline-flex px-4 py-2 bg-herbal-deep text-white font-medium rounded hover:bg-herbal-deep/90 transition-colors text-sm">Clear all filters</a>
                </div>
            @else
                <!-- Grid -->
                <div class="grid grid-cols-2 xl:grid-cols-3 gap-3 md:gap-4">
                    @foreach($products as $product)
                    @php
                        $inWishlist = in_array($product->id, $wishlistIds ?? []);
                        $avg        = round((float) ($product->reviews_avg_rating ?? 0), 1);
                        $count      = (int) ($product->reviews_count ?? 0);
                    @endphp
                    <div class="group bg-white border border-soft-border rounded-lg overflow-hidden hover-electric flex flex-col h-full relative shadow-sm">

                        @if($product->sale_price)
                            <div class="absolute top-2.5 left-2.5 z-10">
                                <span class="px-2 py-0.5 text-error text-[10px] font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                            </div>
                        @endif

                        <div class="absolute top-2.5 right-2.5 z-20">
                            @auth
                                <button type="button" onclick="toggleWishlist({{ $product->id }})" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}" aria-label="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                    <span class="wishlist-icon-{{ $product->id }} material-symbols-outlined text-[18px] {{ $inWishlist ? 'text-herbal-accent' : 'text-on-surface-variant hover:text-herbal-accent' }}" style="font-variation-settings: 'FILL' {{ $inWishlist ? '1' : '0' }};">favorite</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-colors text-on-surface-variant hover:text-herbal-accent" title="Log in to save">
                                    <span class="material-symbols-outlined text-[18px]">favorite</span>
                                </a>
                            @endauth
                        </div>

                        <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square bg-surface-container overflow-hidden relative">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                    <span class="material-symbols-outlined text-5xl">image</span>
                                </div>
                            @endif
                        </a>

                        <div class="p-3 md:p-4 flex flex-col flex-grow">
                            <div class="text-[10px] font-semibold text-herbal-accent mb-1 tracking-wider uppercase line-clamp-1">{{ $product->category->name ?? 'Ayurvedic' }}</div>
                            <a href="{{ route('product.show', $product->slug) }}" class="font-headline-sm text-sm font-semibold text-herbal-deep leading-tight hover:text-herbal-accent transition-colors line-clamp-1 mb-1.5">
                                {{ $product->name }}
                            </a>

                            <!-- Rating (real, from approved reviews) -->
                            <div class="flex items-center gap-1 mb-2 h-4">
                                @if($count > 0)
                                    @foreach(kareon_stars($avg) as $starIcon)
                                        <span class="material-symbols-outlined text-[13px]" style="color:#F5A623;font-variation-settings: 'FILL' {{ $starIcon === 'star_outline' ? '0' : '1' }};" aria-hidden="true">{{ $starIcon === 'star_outline' ? 'star' : $starIcon }}</span>
                                    @endforeach
                                    <span class="text-[11px] text-on-surface-variant ml-1">{{ number_format($avg, 1) }} ({{ $count }})</span>
                                @else
                                    <span class="text-[11px] text-on-surface-variant/70">No reviews yet</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-2.5 flex items-center justify-between border-t border-soft-border group-hover:border-herbal-accent/20 transition-colors">
                                <div class="flex flex-col">
                                    @if($product->sale_price)
                                        <span class="text-[11px] text-on-surface-variant line-through">₹{{ number_format($product->price, 2) }}</span>
                                        <span class="text-base font-bold text-herbal-deep">₹{{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        <span class="text-base font-bold text-herbal-deep">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST" class="js-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-9 h-9 rounded-full border border-herbal-accent text-herbal-accent flex items-center justify-center hover:bg-herbal-deep hover:text-white transition-colors active:scale-95 shadow-sm" title="Add to Cart" aria-label="Add {{ $product->name }} to cart">
                                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 0;">add_shopping_cart</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center w-full">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
