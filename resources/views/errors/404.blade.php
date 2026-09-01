@extends('layouts.app')

@section('title', 'Page Not Found')
@section('meta_description', 'The page you were looking for could not be found. Explore our Ayurvedic herbal products instead.')
@section('no_index', 'true')

@php
    // Kept local to this view (rather than a global composer) and wrapped in
    // a try/catch so that if the DB itself is the reason the request failed,
    // the 404 page still renders — just without the product suggestions —
    // instead of throwing a second exception on top of the first.
    try {
        $notFoundFeatured = \App\Models\Product::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->with('category:id,name,slug')
            ->take(4)
            ->get();

        $notFoundCategories = \App\Models\Category::where('status', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(4)
            ->get();
    } catch (\Throwable $e) {
        $notFoundFeatured = collect();
        $notFoundCategories = collect();
    }
@endphp

@section('content')
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-16 md:py-24 text-center">
    <p class="text-8xl md:text-9xl font-display font-bold text-primary/20 mb-2">404</p>
    <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg font-bold text-herbal-deep mb-4">
        We couldn't find that page
    </h1>
    <p class="text-body-md text-on-surface-variant max-w-xl mx-auto mb-8">
        The page you're looking for may have been moved, renamed, or no longer exists.
        Let's get you back on track.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-3 mb-16">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-[18px]">home</span> Back to Home
        </a>
        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 border border-outline-variant text-on-surface px-6 py-2.5 rounded-lg font-medium hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[18px]">storefront</span> Continue Shopping
        </a>
    </div>

    @if($notFoundCategories->count() > 0)
    <div class="mb-16">
        <h2 class="text-lg font-bold text-on-surface mb-4">Popular Categories</h2>
        <div class="flex flex-wrap items-center justify-center gap-2">
            @foreach($notFoundCategories as $category)
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-primary hover:text-white hover:border-primary transition-colors">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($notFoundFeatured->count() > 0)
    <div>
        <h2 class="text-lg font-bold text-on-surface mb-6">You Might Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 text-left">
            @foreach($notFoundFeatured as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="group block bg-surface border border-outline-variant rounded-xl overflow-hidden hover:border-primary transition-colors shadow-sm">
                    <div class="aspect-square bg-surface-container overflow-hidden">
                        @if($product->main_image)
                            <img src="{{ image_url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="text-[11px] uppercase tracking-wider text-on-surface-variant mb-1">{{ $product->category->name ?? 'Ayurvedic' }}</p>
                        <p class="text-sm font-semibold text-on-surface line-clamp-1 group-hover:text-primary transition-colors">{{ $product->name }}</p>
                        <p class="text-sm font-bold text-herbal-deep mt-1">₹{{ number_format($product->sale_price ?? $product->price, 2) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</main>
@endsection
