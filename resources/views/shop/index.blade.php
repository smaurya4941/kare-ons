@extends('layouts.app')

@section('title', 'Shop - Kare Ons Herbal')

@section('content')
<!-- Page Header -->
<div class="bg-surface-container-lowest pt-20 pb-12 border-b border-outline-variant">
    <div class="max-w-container-max mx-auto px-margin-desktop text-center">
        <h1 class="text-4xl font-semibold text-on-surface mb-4">Shop Ayurvedic Products</h1>
        <p class="text-secondary max-w-2xl mx-auto">Discover our collection of 100% natural and effective herbal remedies, thoughtfully prepared to support your wellness journey.</p>
    </div>
</div>

<div class="max-w-container-max mx-auto px-margin-desktop py-12" x-data="{ mobileFiltersOpen: false }">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Mobile filter dialog -->
        <div class="relative z-40 lg:hidden" role="dialog" aria-modal="true" x-show="mobileFiltersOpen">
            <div class="fixed inset-0 bg-black bg-opacity-25" x-show="mobileFiltersOpen" x-transition.opacity></div>
            <div class="fixed inset-0 z-40 flex" x-show="mobileFiltersOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                <div class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                    <div class="flex items-center justify-between px-4">
                        <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                        <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400" @click="mobileFiltersOpen = false">
                            <span class="sr-only">Close menu</span>
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Mobile Filters Form -->
                    <form class="mt-4 border-t border-gray-200" action="{{ route('shop.index') }}" method="GET">
                        <!-- Category Filter -->
                        <div class="px-4 py-6 border-b border-gray-200">
                            <h3 class="-mx-2 -my-3 flow-root">
                                <span class="font-medium text-gray-900 px-2">Categories</span>
                            </h3>
                            <div class="pt-6">
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input id="cat-mobile-all" name="category" value="" type="radio" {{ request('category') == '' ? 'checked' : '' }} class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                        <label for="cat-mobile-all" class="ml-3 min-w-0 flex-1 text-gray-500">All Products</label>
                                    </div>
                                    @foreach($categories as $category)
                                    <div class="flex items-center">
                                        <input id="cat-mobile-{{ $category->id }}" name="category" value="{{ $category->slug }}" type="radio" {{ request('category') == $category->slug ? 'checked' : '' }} class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                        <label for="cat-mobile-{{ $category->id }}" class="ml-3 min-w-0 flex-1 text-gray-500">{{ $category->name }}</label>
                                    </div>
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

        <!-- Desktop Filters -->
        <div class="hidden lg:block lg:w-64 flex-shrink-0">
            <form class="space-y-8" action="{{ route('shop.index') }}" method="GET" id="desktop-filter-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <div class="border-b border-outline-variant pb-6">
                    <h3 class="font-semibold text-on-surface mb-4">Categories</h3>
                    <ul class="space-y-3">
                        <li>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="" onchange="document.getElementById('desktop-filter-form').submit()" {{ request('category') == '' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded-full">
                                <span class="text-sm {{ request('category') == '' ? 'text-primary font-medium' : 'text-secondary group-hover:text-on-surface transition' }}">All Products</span>
                            </label>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $category->slug }}" onchange="document.getElementById('desktop-filter-form').submit()" {{ request('category') == $category->slug ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded-full">
                                <span class="text-sm {{ request('category') == $category->slug ? 'text-primary font-medium' : 'text-secondary group-hover:text-on-surface transition' }}">{{ $category->name }}</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border-b border-outline-variant pb-6">
                    <h3 class="font-semibold text-on-surface mb-4">Price Range</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="sr-only" for="min_price">Min</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full border-outline-variant rounded focus:ring-primary focus:border-primary text-sm py-2">
                        </div>
                        <div>
                            <label class="sr-only" for="max_price">Max</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full border-outline-variant rounded focus:ring-primary focus:border-primary text-sm py-2">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-4 bg-surface-container hover:bg-surface-container-high text-on-surface py-2 rounded text-sm font-medium transition border border-outline-variant">Filter Price</button>
                </div>
            </form>
        </div>

        <!-- Product Grid Area -->
        <div class="flex-1">
            <!-- Top Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-outline-variant mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-on-surface">
                        @if(request('search'))
                            Search results for "{{ request('search') }}"
                        @elseif(request('category'))
                            {{ $categories->where('slug', request('category'))->first()->name ?? 'Products' }}
                        @else
                            All Products
                        @endif
                    </h2>
                    <p class="text-sm text-secondary mt-1">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Mobile filter button -->
                    <button type="button" class="lg:hidden inline-flex items-center gap-2 text-sm font-medium text-on-surface hover:text-primary transition" @click="mobileFiltersOpen = true">
                        <span class="material-symbols-outlined text-[20px]">filter_list</span>
                        Filters
                    </button>

                    <form action="{{ route('shop.index') }}" method="GET" class="flex items-center gap-2" id="sort-form">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                        @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                        
                        <label for="sort" class="text-sm text-secondary hidden sm:block">Sort by:</label>
                        <select name="sort" id="sort" onchange="document.getElementById('sort-form').submit()" class="border-outline-variant rounded-lg text-sm py-2 pl-3 pr-10 focus:ring-primary focus:border-primary bg-surface">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-16 bg-surface-container-lowest border border-outline-variant rounded-xl border-dashed">
                    <span class="material-symbols-outlined text-4xl text-secondary mb-3">inventory_2</span>
                    <h3 class="text-lg font-medium text-on-surface">No products found</h3>
                    <p class="text-secondary mt-1 max-w-md mx-auto">We couldn't find any products matching your current filters. Try adjusting your search criteria.</p>
                    <a href="{{ route('shop.index') }}" class="mt-4 inline-flex text-primary hover:text-on-primary-fixed-variant font-medium">Clear all filters</a>
                </div>
            @else
                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="group block border border-outline-variant rounded-xl overflow-hidden hover:border-primary transition-colors bg-surface">
                            <div class="aspect-[4/3] bg-surface-container relative overflow-hidden">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl text-outline">image</span>
                                    </div>
                                @endif
                                
                                @if($product->sale_price)
                                    <div class="absolute top-3 left-3 bg-error text-white text-xs font-bold px-2 py-1 rounded">
                                        SALE
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="text-xs font-medium text-primary mb-2">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                <h3 class="text-on-surface font-semibold text-lg line-clamp-1 group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                                <p class="text-secondary text-sm line-clamp-2 mt-1">{{ Str::limit($product->short_description ?? $product->description, 60) }}</p>
                                
                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-outline-variant">
                                    <div class="flex items-center gap-2">
                                        @if($product->sale_price)
                                            <span class="font-bold text-on-surface text-lg">₹{{ number_format($product->sale_price, 2) }}</span>
                                            <span class="text-sm text-secondary line-through">₹{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="font-bold text-on-surface text-lg">₹{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container group-hover:bg-primary group-hover:text-white text-on-surface transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
