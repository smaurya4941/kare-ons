@extends('layouts.app')

@section('title', 'Shop - AyuPure')

@section('content')
<style>
    .clinical-shadow {
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.03);
    }
    .hover-electric:hover {
        border-color: theme('colors.primary');
        box-shadow: 0 8px 24px rgba(161, 0, 255, 0.08);
        transform: translateY(-2px);
    }
</style>

<div class="pt-20"></div>

<!-- Main Content -->
<main class="flex-grow w-full max-w-7xl mx-auto px-4 md:px-16 py-8" x-data="{ mobileFiltersOpen: false }">
    <!-- Page Header -->
    <div class="mb-12">
        <h1 class="font-display-lg text-4xl md:text-5xl font-bold text-on-background mb-4">
            @if(request('search'))
                Search results for "{{ request('search') }}"
            @elseif(request('category'))
                {{ $categories->where('slug', request('category'))->first()->name ?? 'Products' }}
            @else
                Shop All Products
            @endif
        </h1>
        <p class="font-body-lg text-lg text-on-surface-variant max-w-2xl">
            @if(request('search'))
                Explore our Ayurvedic formulations matching your search query.
            @elseif(request('category'))
                Discover pure, potent Ayurvedic remedies crafted for {{ strtolower($categories->where('slug', request('category'))->first()->name ?? 'holistic wellness') }}.
            @else
                Natural Ayurvedic formulations for complete healthcare support. Crafted with traditional wisdom and modern precision.
            @endif
        </p>
    </div>

    <!-- Mobile filter dialog -->
    <div class="relative z-40 lg:hidden" role="dialog" aria-modal="true" x-show="mobileFiltersOpen" style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity" x-show="mobileFiltersOpen" x-transition.opacity></div>
        <div class="fixed inset-0 z-40 flex" x-show="mobileFiltersOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="relative flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                    <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400" @click="mobileFiltersOpen = false">
                        <span class="sr-only">Close menu</span>
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <!-- Mobile Filters Form -->
                <form class="mt-4 border-t border-gray-200" action="{{ route('shop.index') }}" method="GET">
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                    
                    <div class="px-4 py-6 border-b border-gray-200">
                        <h3 class="-mx-2 -my-3 flow-root">
                            <span class="font-medium text-gray-900 px-2">Search</span>
                        </h3>
                        <div class="pt-6 relative">
                            <span class="material-symbols-outlined absolute left-3 top-8 text-on-surface-variant text-[20px]">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full border border-soft-border rounded py-2 pl-10 pr-3 text-sm focus:border-primary focus:ring-0">
                        </div>
                    </div>
                    
                    <div class="px-4 py-6 border-b border-gray-200">
                        <h3 class="-mx-2 -my-3 flow-root">
                            <span class="font-medium text-gray-900 px-2">Categories</span>
                        </h3>
                        <div class="pt-6">
                            <div class="space-y-4">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input name="category" value="" type="radio" {{ request('category') == '' ? 'checked' : '' }} class="form-radio h-4 w-4 text-primary focus:ring-primary-container transition-colors">
                                    <span class="font-body-md text-base text-on-surface-variant group-hover:text-primary transition-colors">All Products</span>
                                </label>
                                @foreach($categories as $category)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input name="category" value="{{ $category->slug }}" type="radio" {{ request('category') == $category->slug ? 'checked' : '' }} class="form-radio h-4 w-4 text-primary focus:ring-primary-container transition-colors">
                                    <span class="font-body-md text-base text-on-surface-variant group-hover:text-primary transition-colors">{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-6">
                        <button type="submit" class="w-full bg-primary text-white py-2 rounded font-medium">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters (Desktop) -->
        <aside class="hidden lg:block w-full lg:w-1/4 flex-shrink-0">
            <div class="sticky top-28 space-y-8 bg-white p-6 rounded-lg border border-soft-border clinical-shadow">
                <form action="{{ route('shop.index') }}" method="GET" id="desktop-filter-form">
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    <!-- Filter Section: Search -->
                    <div>
                        <h3 class="font-label-md text-sm font-semibold uppercase tracking-wider text-on-surface mb-4">Search Products</h3>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-[20px]">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search AyuPure..." class="w-full border border-soft-border rounded py-2 pl-10 pr-3 text-sm focus:border-primary focus:ring-0">
                        </div>
                    </div>
                    <hr class="border-soft-border my-6"/>

                    <!-- Filter Section: Categories -->
                    <div>
                        <h3 class="font-label-md text-sm font-semibold uppercase tracking-wider text-on-surface mb-4">Categories</h3>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="category" value="" onchange="document.getElementById('desktop-filter-form').submit()" {{ request('category') == '' ? 'checked' : '' }} class="form-radio h-4 w-4 text-primary focus:ring-primary-container transition-colors">
                                <span class="font-body-md text-base {{ request('category') == '' ? 'text-primary font-medium' : 'text-on-surface-variant group-hover:text-primary' }} transition-colors">All Products</span>
                            </label>
                            @foreach($categories as $category)
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $category->slug }}" onchange="document.getElementById('desktop-filter-form').submit()" {{ request('category') == $category->slug ? 'checked' : '' }} class="form-radio h-4 w-4 text-primary focus:ring-primary-container transition-colors">
                                <span class="font-body-md text-base {{ request('category') == $category->slug ? 'text-primary font-medium' : 'text-on-surface-variant group-hover:text-primary' }} transition-colors">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <hr class="border-soft-border my-6"/>
                    
                    <!-- Filter Section: Price -->
                    <div>
                        <h3 class="font-label-md text-sm font-semibold uppercase tracking-wider text-on-surface mb-4">Price Range</h3>
                        <div class="space-y-4">
                            <div class="flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full border border-soft-border rounded py-2 px-3 text-sm focus:border-primary focus:ring-0">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full border border-soft-border rounded py-2 px-3 text-sm focus:border-primary focus:ring-0">
                            </div>
                            <button type="submit" class="w-full py-2 bg-surface-container hover:bg-surface-container-high transition-colors text-sm font-medium rounded text-on-surface border border-soft-border">Apply Price Filter</button>
                        </div>
                    </div>
                    <hr class="border-soft-border my-6"/>
                    
                    <!-- Filter Section: Certification -->
                    <div>
                        <h3 class="font-label-md text-sm font-semibold uppercase tracking-wider text-on-surface mb-4">Certifications</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full font-label-sm text-xs border border-green-200">GMP Certified</span>
                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full font-label-sm text-xs border border-green-200">Ayush Certified</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full font-label-sm text-xs border border-gray-200">100% Vegan</span>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1 w-full">
            <!-- Sort & Controls -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-soft-border">
                <span class="font-body-md text-base text-on-surface-variant">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </span>
                
                <div class="flex items-center gap-4">
                    <button type="button" class="lg:hidden flex items-center gap-1 text-primary font-medium" @click="mobileFiltersOpen = true">
                        <span class="material-symbols-outlined text-[20px]">filter_list</span>
                        Filters
                    </button>
                    
                    <form action="{{ route('shop.index') }}" method="GET" class="flex items-center space-x-2" id="sort-form">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                        @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                        
                        <label class="font-body-md text-sm text-on-surface-variant hidden sm:block" for="sort">Sort by:</label>
                        <select name="sort" id="sort" onchange="document.getElementById('sort-form').submit()" class="form-select bg-transparent border-none text-primary font-label-md text-sm font-medium focus:ring-0 cursor-pointer pr-8">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-16 bg-white border border-soft-border rounded-xl border-dashed">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-3">inventory_2</span>
                    <h3 class="text-xl font-semibold text-on-surface">No products found</h3>
                    <p class="text-on-surface-variant mt-2 max-w-md mx-auto">We couldn't find any products matching your current filters. Try adjusting your search criteria.</p>
                    <a href="{{ route('shop.index') }}" class="mt-4 inline-flex px-4 py-2 bg-primary text-white font-medium rounded hover:bg-primary/90 transition-colors">Clear all filters</a>
                </div>
            @else
                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="group bg-white border border-soft-border rounded-lg overflow-hidden transition-all duration-300 hover-electric flex flex-col h-full relative shadow-sm">
                        
                        <div class="absolute top-3 left-3 z-10 flex gap-2">
                            @if($product->sale_price)
                                <span class="px-2 py-1 bg-error/10 text-error font-label-sm text-xs font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square bg-surface-container overflow-hidden relative">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                    <span class="material-symbols-outlined text-5xl">image</span>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="text-xs font-medium text-primary mb-1 tracking-wider uppercase">{{ $product->category->name ?? 'Ayurvedic' }}</div>
                            <div class="flex justify-between items-start mb-2">
                                <a href="{{ route('product.show', $product->slug) }}" class="font-headline-md text-xl font-semibold text-on-surface leading-tight hover:text-primary transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <p class="font-body-md text-sm text-on-surface-variant line-clamp-2 mb-3">
                                {{ strip_tags($product->short_description ?? $product->description) }}
                            </p>
                            
                            <div class="flex items-center space-x-1 mb-4">
                                <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-yellow-400 text-sm" style="font-variation-settings: 'FILL' 1;">star_half</span>
                                <span class="font-label-sm text-xs text-on-surface-variant ml-1">(4.8)</span>
                            </div>
                            
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-soft-border group-hover:border-primary/20 transition-colors">
                                <div class="flex flex-col">
                                    @if($product->sale_price)
                                        <span class="font-label-sm text-xs text-on-surface-variant line-through">₹{{ number_format($product->price, 2) }}</span>
                                        <span class="font-headline-md text-xl font-bold text-on-background">₹{{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        <span class="font-headline-md text-xl font-bold text-on-background">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-10 h-10 rounded border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-colors active:scale-95 shadow-sm" title="Add to Cart">
                                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">add_shopping_cart</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-12 flex justify-center w-full">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
