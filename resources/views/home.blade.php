@extends('layouts.app')

@section('content')
<style>
    .hover-electric:hover {
        border-color: theme('colors.primary');
        box-shadow: 0 8px 24px rgba(161, 0, 255, 0.08);
        transform: translateY(-2px);
    }
</style>

<!-- Hero Section -->
<section class="relative h-[90vh] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        @php
            $hero = $banners->where('type', 'hero')->first();
        @endphp
        @if($hero)
            <div class="w-full h-full bg-cover bg-center opacity-40" data-alt="Hero Background" style='background-image: url("{{ asset('storage/' . $hero->desktop_image) }}");'></div>
        @else
            <div class="w-full h-full bg-cover bg-center opacity-40" data-alt="Hero Background" style='background-image: url("{{ setting('home_hero_bg') ? asset('storage/' . setting('home_hero_bg')) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAwFEioZmdWOcM4DVlVqnXarX4GXg6E_3pqPGJ9qld0IO7yJ_CZhfX3Zwp-qnfpt__FyCN0_K5j531wTlQtqctdZg3Gx1pIH1uh9nusroOJkFg-4k2bteEfXQ3FfZQIDnCXtiDUChXHGayo4eI99Ax69rK1C_Q5P-r5_2mvlw808GrBotWHQg3L3Yq3SXQFczGMbSu5GDBz2jZMqe-gC6IKOGxWpGBYz3K0_d73R6J4v4oHQVyVshr45hwO4DUnZ9rqUUIVRqOPdnU' }}");'></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-background via-background/80 to-transparent"></div>
    </div>
    
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop relative z-10 w-full">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full mb-8">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span class="text-label-sm font-bold uppercase tracking-wider">GMP Certified Excellence</span>
            </div>
            <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-background mb-6">{!! setting('home_hero_title', 'Nature\'s Wisdom, <br/><span class="text-secondary">Refined by Science.</span>') !!}</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-10 leading-relaxed">
                {!! setting('home_hero_subtitle', 'Pioneering clinical-grade Ayurvedic medicine through rigorous scientific validation. Our state-of-the-art manufacturing facilities deliver holistic wellness solutions that honor ancient traditions while meeting modern pharmaceutical standards.') !!}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ setting('home_cta_link', route('shop.index')) }}" class="bg-primary text-on-primary px-10 py-4 rounded-full font-label-md text-label-md hover:scale-105 transition-transform duration-300">{{ setting('home_cta_text', 'Start Your Inquiry') }}</a>
            </div>
        </div>
    </div>
</section>

<!-- Trusted Logos -->
<section class="bg-surface py-12 border-y border-outline-variant/20">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <p class="text-center text-label-sm text-outline uppercase tracking-[0.2em] mb-8 font-bold">Standard of Excellence Certifications</p>
        <div class="flex flex-wrap justify-between items-center gap-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
            <div class="flex items-center gap-2"><span class="text-headline-sm font-bold">GMP </span><span class="text-label-sm">Certified</span></div>
            <div class="flex items-center gap-2"><span class="text-headline-sm font-bold">ISO 9001:2015</span></div>
            <div class="flex items-center gap-2"><span class="text-headline-sm font-bold">AYUSH</span><span class="text-label-sm">Premium Mark</span></div>
            <div class="flex items-center gap-2"><span class="text-headline-sm font-bold">PAN INDIA</span><span class="text-label-sm">Distribution</span></div>
            <div class="flex items-center gap-2"><span class="text-headline-sm font-bold">FDA</span><span class="text-label-sm">Compliant</span></div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
@if($homepageCategories->count() > 0)
<section class="py-section-gap bg-surface-container-low">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="mb-12">
            <span class="text-label-sm font-bold text-secondary uppercase tracking-widest mb-3 block">Our Portfolio</span>
            <h2 class="font-headline-md text-headline-md text-on-background">Curated Ayurvedic Collections</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            @foreach($homepageCategories as $category)
            <div class="group relative overflow-hidden rounded-xl clinical-card h-[400px]">
                @php $categoryImage = $category->banner_image ?? $category->image; @endphp
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="{{ $category->name }}" src="{{ $categoryImage ? asset('storage/'.$categoryImage) : 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=800&auto=format&fit=crop' }}"/>
                <div class="absolute inset-0 portfolio-overlay"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white w-full">
                    <h3 class="font-headline-sm text-headline-sm mb-4 text-white">{{ $category->name }}</h3>
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="inline-block text-label-md font-bold text-white border-b-2 border-secondary pb-1 hover:text-secondary-fixed transition-all">Explore Range</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Best Sellers -->
@if($bestSellers->count() > 0)
<section class="py-section-gap bg-white">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="mb-12 flex justify-between items-end">
            <div>
                <span class="text-label-sm font-bold text-secondary uppercase tracking-widest mb-3 block">Most Loved</span>
                <h2 class="font-headline-md text-headline-md text-on-background">Best Sellers</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-secondary font-medium hover:underline text-sm hidden md:block">View All</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($bestSellers as $product)
                <div class="group bg-white border border-soft-border rounded-lg overflow-hidden transition-all duration-300 hover-electric flex flex-col h-full relative shadow-sm">
                    @if($product->sale_price)
                        <div class="absolute top-3 left-3 z-10 flex gap-2">
                            <span class="px-2 py-1 bg-error/10 text-error font-label-sm text-xs font-bold rounded backdrop-blur-sm bg-white/90">SALE</span>
                        </div>
                    @endif
                    
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
    </div>
</section>
@endif

<!-- Expert Spotlight (Static but nice to keep) -->
<section class="py-section-gap overflow-hidden bg-surface-container-low">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="bg-white border border-outline-variant/30 rounded-3xl overflow-hidden flex flex-col lg:flex-row items-stretch">
            <div class="lg:w-1/2 h-96 lg:h-auto relative">
                <div class="w-full h-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB2dyUmn2OYzoxeJ34xYl2v30ARYuhHNow-zAi4ZwdAx1cWv0H-ZrJSXznQnuwKcLzu9wApe62fZHxZeeN1UNhJZQ2OoqM5StXwR3rVcLXLaZVM6PUwfgGrAB8v35WGMhKk3sEwICtCODP0Z5_Es5jUGeH2Gl1H5SmK_a61zwgHXvt8z1qwYCfRQs3MWk9scA83f3f15WchryT4fX5ifLXoOF-ty0ERSuC0k0cb00gCZ9B3Fh4NLMxHwIshuXyCaPAykPryDmsix7c");'></div>
            </div>
            <div class="lg:w-1/2 p-12 lg:p-20 flex flex-col justify-center bg-surface-container-lowest">
                <span class="material-symbols-outlined text-secondary text-[48px] mb-8" style='font-variation-settings: "FILL" 1;'>format_quote</span>
                <h3 class="font-headline-md text-headline-md mb-6 italic leading-snug">"True healing occurs when we harmonize the elemental wisdom of nature with the diagnostic precision of science."</h3>
                <div class="mb-10">
                    <p class="font-headline-sm text-headline-sm text-primary">Dr. Rajni Dubey</p>
                    <p class="text-secondary font-label-md">Expert Ayurvedic Vaidya (B.A.M.S)</p>
                    <p class="text-on-surface-variant text-label-sm mt-2">20+ Years Clinical Practice &amp; Formulation Research</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="bg-primary-container py-section-gap">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="font-headline-md text-headline-md text-on-primary mb-4">Our Ethos</h2>
            <p class="text-on-primary-container font-body-md">The core principles that guide our clinical excellence and pharmaceutical integrity.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
                    <span class="material-symbols-outlined text-secondary-fixed text-[32px]">shield_person</span>
                </div>
                <h4 class="text-on-primary font-headline-sm mb-4">Integrity</h4>
                <p class="text-on-primary-container text-label-md">Absolute transparency in sourcing and manufacturing for unwavering trust.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
                    <span class="material-symbols-outlined text-secondary-fixed text-[32px]">groups</span>
                </div>
                <h4 class="text-on-primary font-headline-sm mb-4">Teamwork</h4>
                <p class="text-on-primary-container text-label-md">Collaborative intelligence of scientists, vaidyas, and process engineers.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
                    <span class="material-symbols-outlined text-secondary-fixed text-[32px]">eco</span>
                </div>
                <h4 class="text-on-primary font-headline-sm mb-4">Pure Ayurveda</h4>
                <p class="text-on-primary-container text-label-md">Upholding the sanctity of ancient recipes with pharmaceutical precision.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
                    <span class="material-symbols-outlined text-secondary-fixed text-[32px]">psychology_alt</span>
                </div>
                <h4 class="text-on-primary font-headline-sm mb-4">Innovation</h4>
                <p class="text-on-primary-container text-label-md">Continuous R&amp;D to improve bioavailability and therapeutic delivery.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
@if($testimonials->count() > 0)
<section class="py-section-gap bg-surface-container-low">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-12">
            <h2 class="font-headline-md text-headline-md text-on-background">What Our Patients Say</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <div class="flex text-yellow-400 mb-4">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        @endfor
                    </div>
                    <p class="text-on-surface-variant italic mb-6">"{{ $testimonial->content }}"</p>
                </div>
                <div>
                    <p class="font-bold text-primary">{{ $testimonial->name }}</p>
                    @if($testimonial->designation)
                        <p class="text-sm text-secondary">{{ $testimonial->designation }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
