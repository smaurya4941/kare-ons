@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)

@section('content')
<div class="bg-background">
    <!-- Breadcrumbs -->
    <div class="max-w-container-max mx-auto px-margin-desktop py-6">
        <nav class="flex text-sm text-secondary font-medium font-body">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="mx-2 text-outline-variant">/</span>
            <a href="#" class="hover:text-primary transition-colors">{{ $product->category->name ?? 'Shop' }}</a>
            <span class="mx-2 text-outline-variant">/</span>
            <span class="text-on-surface">{{ $product->name }}</span>
        </nav>
    </div>

    <div class="max-w-container-max mx-auto px-margin-desktop pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <!-- Left: Product Images -->
            <div x-data="{ mainImage: '{{ asset('storage/' . $product->main_image) }}' }" class="flex flex-col-reverse lg:flex-row gap-6">
                <!-- Thumbnails (Mobile: Row, Desktop: Col) -->
                <div class="flex lg:flex-col gap-4 overflow-x-auto lg:overflow-visible no-scrollbar pb-2 lg:pb-0 lg:w-24 flex-shrink-0">
                    <button @click="mainImage = '{{ asset('storage/' . $product->main_image) }}'" class="w-20 h-20 lg:w-full lg:h-24 rounded-lg overflow-hidden border-2 focus:outline-none transition-colors" :class="mainImage === '{{ asset('storage/' . $product->main_image) }}' ? 'border-primary' : 'border-transparent hover:border-outline-variant'">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                    </button>
                    @foreach($product->images as $image)
                        <button @click="mainImage = '{{ asset('storage/' . $image->image_path) }}'" class="w-20 h-20 lg:w-full lg:h-24 rounded-lg overflow-hidden border-2 focus:outline-none transition-colors" :class="mainImage === '{{ asset('storage/' . $image->image_path) }}' ? 'border-primary' : 'border-transparent hover:border-outline-variant'">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                
                <!-- Main Image -->
                <div class="flex-grow rounded-lg overflow-hidden bg-surface-container border border-outline-variant aspect-square lg:aspect-auto lg:h-[600px] relative">
                    <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover object-center mix-blend-multiply">
                    @if($product->sale_price)
                        <div class="absolute top-4 left-4 bg-error text-on-error text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-sm shadow-sm">
                            SALE
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="flex flex-col">
                <div class="mb-6">
                    <span class="text-xs font-bold text-primary uppercase tracking-widest mb-3 block">{{ $product->category->name ?? 'Premium Ayurveda' }}</span>
                    <h1 class="font-display text-4xl md:text-5xl font-semibold text-on-surface leading-[1.1] mb-4">{{ $product->name }}</h1>
                    
                    <!-- Reviews Summary -->
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="flex text-amber-500">
                            @for($i = 0; $i < 5; $i++)
                                <span class="material-symbols-outlined text-[20px] {{ $i < 4 ? 'text-amber-500' : 'text-outline-variant' }}">star</span>
                            @endfor
                        </div>
                        <span class="text-sm font-medium text-secondary">({{ $product->reviews->count() }} Reviews)</span>
                    </div>

                    <!-- Price -->
                    <div class="flex items-end space-x-3 mb-6">
                        @if($product->sale_price)
                            <span class="text-3xl font-semibold text-on-surface">₹{{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-xl font-medium text-secondary line-through mb-1">₹{{ number_format($product->price, 2) }}</span>
                            <span class="text-xs font-bold bg-tertiary-container text-on-tertiary-container px-2 py-1 rounded-sm mb-2 ml-2 uppercase tracking-wider">Save ₹{{ number_format($product->price - $product->sale_price, 2) }}</span>
                        @else
                            <span class="text-3xl font-semibold text-on-surface">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Short Description -->
                <div class="font-body text-secondary text-lg leading-relaxed mb-8 border-t border-outline-variant pt-6">
                    <p>{{ $product->short_description ?? 'A premium ayurvedic blend crafted for natural healing and wellness. Meticulously developed by our specialists in our GMP-certified units.' }}</p>
                </div>

                <!-- Add to Cart Form -->
                <form action="#" method="POST" class="mb-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center border border-outline-variant rounded-lg w-full sm:w-32 bg-surface" x-data="{ qty: 1 }">
                            <button type="button" @click="if(qty > 1) qty--" class="w-10 h-12 flex justify-center items-center text-secondary hover:text-primary transition-colors focus:outline-none">
                                <span class="material-symbols-outlined text-[20px]">remove</span>
                            </button>
                            <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock_quantity }}" class="w-full text-center border-none focus:ring-0 text-on-surface font-semibold p-0 bg-transparent" readonly>
                            <button type="button" @click="if(qty < {{ $product->stock_quantity }}) qty++" class="w-10 h-12 flex justify-center items-center text-secondary hover:text-primary transition-colors focus:outline-none">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                            </button>
                        </div>
                        
                        <button type="submit" class="flex-1 bg-primary text-white text-sm font-bold uppercase tracking-widest py-3 px-8 rounded-lg hover:bg-on-primary-fixed-variant transition-all shadow-sm flex justify-center items-center gap-2" {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                            <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                            <span>{{ $product->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                        </button>
                    </div>
                    
                    @if($product->stock_quantity > 0 && $product->stock_quantity <= 5)
                        <p class="text-xs font-bold text-error uppercase tracking-widest mt-4">Hurry! Only {{ $product->stock_quantity }} left in stock.</p>
                    @endif
                </form>

                <!-- Meta Info -->
                <div class="space-y-4 pt-6 border-t border-outline-variant text-sm text-secondary font-medium">
                    <div class="flex items-center">
                        <span class="w-32 text-on-surface">SKU</span>
                        <span class="font-mono text-xs">{{ $product->sku }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-32 text-on-surface">Category</span>
                        <a href="#" class="text-primary hover:underline">{{ $product->category->name ?? 'N/A' }}</a>
                    </div>
                    <div class="flex items-center">
                        <span class="w-32 text-on-surface">Weight</span>
                        <span>{{ $product->weight ? $product->weight . ' g' : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Long Description & Details Tabs -->
    <div class="bg-surface-container py-16 border-y border-outline-variant" x-data="{ tab: 'description' }">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <!-- Tab Headers -->
            <div class="flex space-x-12 border-b border-outline-variant overflow-x-auto no-scrollbar">
                <button @click="tab = 'description'" class="pb-4 text-sm font-bold uppercase tracking-widest transition-colors whitespace-nowrap focus:outline-none" :class="tab === 'description' ? 'text-primary border-b-2 border-primary' : 'text-secondary hover:text-on-surface'">
                    Product Description
                </button>
                <button @click="tab = 'ingredients'" class="pb-4 text-sm font-bold uppercase tracking-widest transition-colors whitespace-nowrap focus:outline-none" :class="tab === 'ingredients' ? 'text-primary border-b-2 border-primary' : 'text-secondary hover:text-on-surface'">
                    Key Ingredients
                </button>
                <button @click="tab = 'reviews'" class="pb-4 text-sm font-bold uppercase tracking-widest transition-colors whitespace-nowrap focus:outline-none" :class="tab === 'reviews' ? 'text-primary border-b-2 border-primary' : 'text-secondary hover:text-on-surface'">
                    Customer Reviews ({{ $product->reviews->count() }})
                </button>
            </div>
            
            <!-- Tab Contents -->
            <div class="py-10">
                <!-- Description Tab -->
                <div x-show="tab === 'description'" class="max-w-4xl font-body text-secondary leading-relaxed space-y-4" x-transition>
                    {!! nl2br(e($product->description)) !!}
                </div>
                
                <!-- Ingredients Tab -->
                <div x-show="tab === 'ingredients'" class="max-w-4xl font-body text-secondary leading-relaxed" style="display: none;" x-transition>
                    <p>Information about ayurvedic ingredients will be displayed here. Our ingredients are sourced directly from certified organic farms.</p>
                </div>
                
                <!-- Reviews Tab -->
                <div x-show="tab === 'reviews'" style="display: none;" x-transition>
                    @if($product->reviews->isEmpty())
                        <p class="text-secondary italic">No reviews yet. Be the first to review this product!</p>
                    @else
                        <div class="space-y-6 max-w-4xl">
                            @foreach($product->reviews as $review)
                                <div class="bg-surface p-6 rounded-lg shadow-sm carbon-border">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-lg">
                                                {{ substr($review->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-on-surface">{{ $review->user->name ?? 'Guest' }}</h4>
                                                <span class="text-xs font-medium text-secondary">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex text-amber-500">
                                            @for($i = 0; $i < 5; $i++)
                                                <span class="material-symbols-outlined text-[18px] {{ $i < $review->rating ? 'text-amber-500' : 'text-outline-variant' }}">star</span>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->title)
                                        <h5 class="font-semibold text-on-surface mb-2">{{ $review->title }}</h5>
                                    @endif
                                    <p class="text-secondary text-sm leading-relaxed">{{ $review->review }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap">
        <span class="text-xs font-bold text-primary uppercase tracking-widest mb-3 block">Explore More</span>
        <h2 class="text-3xl font-semibold text-on-surface mb-10">You May Also Like</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relatedProducts as $related)
                <div class="group">
                    <div class="relative aspect-[4/5] overflow-hidden rounded-lg bg-surface-container mb-4 carbon-border">
                        @if($related->main_image)
                            <img src="{{ asset('storage/' . $related->main_image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-outline">No Image</div>
                        @endif
                    </div>
                    <h3 class="text-lg font-semibold text-on-surface line-clamp-1 mb-1">
                        <a href="{{ route('product.show', $related->slug) }}" class="hover:text-primary transition-colors">
                            <span class="absolute inset-0"></span>
                            {{ $related->name }}
                        </a>
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-semibold text-on-surface">₹{{ number_format($related->sale_price ?? $related->price, 2) }}</span>
                        @if($related->sale_price)
                            <span class="text-xs font-medium text-secondary line-through">₹{{ number_format($related->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
