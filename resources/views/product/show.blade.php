@extends('layouts.app')

@section('title', ($product->meta_title ?? $product->name) . ' - Kare Ons Herbal')

@section('content')
<style>
    .btn-squish:active { transform: scale(0.95); transition: transform 0.1s; }
    .hover-border-primary:hover { border-color: theme('colors.primary'); transition: border-color 0.3s; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .product-content { font-size: 0.95rem; line-height: 1.5; }
    .product-content ul { list-style-type: disc; padding-left: 1.25rem; margin-top: 0.25rem; margin-bottom: 0.75rem; }
    .product-content li { margin-bottom: 0; }
    .product-content strong { font-weight: 700; font-size: 1rem; display: block; margin-top: 1rem; margin-bottom: 0.25rem; color: #1f2937; }
    /* Hide <br> tags injected by nl2br inside block elements */
    .product-content ul br, .product-content ol br, .product-content table br, .product-content tbody br, .product-content tr br { display: none; }
</style>

<main class="py-6 md:py-8 px-4 md:px-16 max-w-7xl mx-auto">
    
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-on-surface-variant mb-6 font-body-md">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
            <li><span class="material-symbols-outlined text-[14px]">chevron_right</span></li>
            <li><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-primary transition-colors">{{ $product->category->name }}</a></li>
            <li><span class="material-symbols-outlined text-[14px]">chevron_right</span></li>
            <li class="text-on-surface font-medium line-clamp-1" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Hero Product Section -->
    <section class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">
        
        <!-- Left: Image Gallery -->
        <div class="md:col-span-5 flex flex-col gap-3" x-data="{ 
            activeImage: '{{ asset('storage/' . $product->main_image) }}',
            images: [
                '{{ asset('storage/' . $product->main_image) }}',
                @foreach($product->images as $image)
                    '{{ asset('storage/' . $image->image_path) }}',
                @endforeach
            ]
        }">
            <!-- Main Image -->
            <div class="bg-white rounded-xl border border-soft-border p-4 flex items-center justify-center relative hover-border-primary overflow-hidden aspect-square shadow-sm">
                <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at center, #a100ff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-contain z-10 relative transition-transform duration-500 hover:scale-105">
                @if($product->sale_price)
                    <div class="absolute top-3 left-3 z-20 bg-error/10 text-error font-label-sm text-[10px] font-bold px-2 py-1 rounded shadow-sm backdrop-blur-sm bg-white/90">
                        SALE
                    </div>
                @endif
            </div>

            <!-- Thumbnails -->
            <div class="grid grid-cols-5 gap-2">
                <button @click="activeImage = images[0]" class="aspect-square rounded-lg border-2 overflow-hidden bg-white hover:border-primary transition-colors" :class="activeImage === images[0] ? 'border-primary' : 'border-soft-border'">
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image" class="w-full h-full object-cover">
                </button>
                @foreach($product->images as $index => $image)
                    <button @click="activeImage = images[{{ $index + 1 }}]" class="aspect-square rounded-lg border-2 overflow-hidden bg-white hover:border-primary transition-colors" :class="activeImage === images[{{ $index + 1 }}] ? 'border-primary' : 'border-soft-border'">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery Image {{ $index + 1 }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Right: Product Info -->
        <div class="md:col-span-7 flex flex-col justify-start py-2">
            <div class="flex gap-2 mb-3">
                <span class="bg-green-50 text-green-700 font-label-sm text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-widest border border-green-100">Ayurvedic</span>
                <span class="bg-blue-50 text-blue-700 font-label-sm text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-widest border border-blue-100">GMP Certified</span>
            </div>
            
            <h1 class="font-display-lg text-2xl lg:text-3xl font-bold text-on-surface mb-2 leading-tight">{{ $product->name }}</h1>
            
            <div class="flex flex-col gap-1 mb-4">
                <div class="flex items-center gap-2">
                    <div class="flex text-amber-400">
                        @php
                            $avgRating = collect($product->reviews)->avg('rating') ?? 5;
                            $totalReviews = collect($product->reviews)->count();
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $avgRating)
                                <span class="material-symbols-outlined text-[16px] fill-current" style="font-variation-settings: 'FILL' 1;">star</span>
                            @else
                                <span class="material-symbols-outlined text-[16px]">star</span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-xs font-medium text-on-surface-variant">({{ $totalReviews }} Reviews)</span>
                </div>
                <div class="text-xs font-medium text-on-surface-variant space-x-2 mt-1">
                    <span>SKU: {{ $product->sku }}</span>
                    @if($product->brand)<span>| Brand: <span class="text-on-surface font-bold">{{ $product->brand }}</span></span>@endif
                    @if($product->pack_size)<span>| Pack Size: <span class="text-on-surface font-bold">{{ $product->pack_size }}</span></span>@endif
                </div>
            </div>

            <p class="font-body-md text-sm text-on-surface-variant mb-5 leading-relaxed line-clamp-3">
                {{ strip_tags($product->short_description ?? Str::limit($product->description, 150)) }}
            </p>

            <div class="flex items-end gap-3 mb-6">
                @if($product->sale_price)
                    <span class="font-headline-lg text-3xl font-bold text-on-surface">₹{{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-lg text-on-surface-variant line-through mb-0.5 font-medium">₹{{ number_format($product->price, 2) }}</span>
                    @php
                        $saved = $product->price - $product->sale_price;
                        $percent = round(($saved / $product->price) * 100);
                    @endphp
                    <span class="bg-error/10 text-error font-label-sm text-[10px] font-bold px-1.5 py-0.5 rounded mb-1">{{ $percent }}% OFF</span>
                @else
                    <span class="font-headline-lg text-3xl font-bold text-on-surface">₹{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="bg-white p-5 rounded-xl border border-soft-border shadow-sm mb-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="mb-4 flex items-center gap-2 font-medium text-xs">
                    @if($product->stock_quantity > 10)
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-700">In Stock & Ready to Ship</span>
                    @elseif($product->stock_quantity > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-amber-700">Limited Stock (Only {{ $product->stock_quantity }} left)</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-error"></span>
                        <span class="text-error">Out of Stock</span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex items-center border border-soft-border rounded-lg overflow-hidden bg-surface" x-data="{ qty: 1 }">
                        <button type="button" @click="if(qty > 1) qty--" class="px-3 py-2 hover:bg-surface-container transition-colors text-on-surface">
                            <span class="material-symbols-outlined text-[18px]">remove</span>
                        </button>
                        <input type="number" name="quantity" id="quantity" x-model="qty" min="1" max="{{ $product->stock_quantity > 0 ? $product->stock_quantity : 1 }}" class="w-12 text-center text-sm border-none focus:ring-0 text-on-surface font-medium bg-transparent" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                        <button type="button" @click="if(qty < {{ $product->stock_quantity }}) qty++" class="px-3 py-2 hover:bg-surface-container transition-colors text-on-surface">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                        </button>
                    </div>

                    <button type="submit" name="action" value="cart" class="btn-squish flex-1 bg-primary text-white font-body-md text-sm font-medium py-2.5 px-5 rounded-lg shadow-sm hover:bg-primary/90 transition-colors flex items-center justify-center gap-2" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">shopping_bag</span>
                        Add to Cart
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-5 text-on-surface-variant font-body-md text-xs border-t border-soft-border pt-5">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[18px]">local_shipping</span>
                    <span>Free shipping over ₹500</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[18px]">verified</span>
                    <span>100% Authentic</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Details Section -->
    <section class="mb-20 max-w-6xl mx-auto">
        <h2 class="font-display-lg text-3xl font-bold text-on-surface mb-8">Product Specifications & Details</h2>
        
        <div class="border border-soft-border rounded-xl bg-white shadow-sm flex flex-col md:flex-row overflow-hidden" x-data="{ activeTab: 'description' }">
            
            <!-- Sidebar Navigation (Desktop) / Top Scrollable (Mobile) -->
            <div class="md:w-1/4 bg-surface border-b md:border-b-0 md:border-r border-soft-border flex md:flex-col overflow-x-auto hide-scrollbar">
                <button @click="activeTab = 'description'" class="px-6 py-5 text-left font-bold text-sm md:text-base whitespace-nowrap transition-colors" :class="activeTab === 'description' ? 'bg-white text-primary border-b-2 md:border-b-0 md:border-l-4 border-primary' : 'text-on-surface-variant hover:bg-white/50'">
                    Product Description
                </button>
                @if($product->benefits)
                <button @click="activeTab = 'benefits'" class="px-6 py-5 text-left font-bold text-sm md:text-base whitespace-nowrap transition-colors" :class="activeTab === 'benefits' ? 'bg-white text-primary border-b-2 md:border-b-0 md:border-l-4 border-primary' : 'text-on-surface-variant hover:bg-white/50'">
                    Health Benefits
                </button>
                @endif
                @if($product->ingredients)
                <button @click="activeTab = 'ingredients'" class="px-6 py-5 text-left font-bold text-sm md:text-base whitespace-nowrap transition-colors" :class="activeTab === 'ingredients' ? 'bg-white text-primary border-b-2 md:border-b-0 md:border-l-4 border-primary' : 'text-on-surface-variant hover:bg-white/50'">
                    Ingredients
                </button>
                @endif
                @if($product->usage_instructions || $product->storage_instructions || $product->ayurvedic_reference || $product->suitable_for || $product->precautions)
                <button @click="activeTab = 'usage'" class="px-6 py-5 text-left font-bold text-sm md:text-base whitespace-nowrap transition-colors" :class="activeTab === 'usage' ? 'bg-white text-primary border-b-2 md:border-b-0 md:border-l-4 border-primary' : 'text-on-surface-variant hover:bg-white/50'">
                    Usage & Context
                </button>
                @endif
            </div>

            <!-- Content Area -->
            <div class="md:w-3/4 p-6 md:p-8 min-h-[400px]">
                
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="product-content text-on-surface-variant" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    {!! nl2br($product->description) !!}
                </div>
                
                <!-- Benefits Tab -->
                @if($product->benefits)
                <div x-show="activeTab === 'benefits'" class="product-content text-on-surface-variant" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    {!! nl2br($product->benefits) !!}
                </div>
                @endif

                <!-- Ingredients Tab -->
                @if($product->ingredients)
                <div x-show="activeTab === 'ingredients'" class="w-full overflow-x-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="prose max-w-none w-full">
                        {!! $product->ingredients !!}
                    </div>
                </div>
                @endif

                <!-- Usage & Context Tab -->
                @if($product->usage_instructions || $product->storage_instructions || $product->ayurvedic_reference || $product->suitable_for || $product->precautions)
                <div x-show="activeTab === 'usage'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if($product->usage_instructions || $product->storage_instructions)
                        <div class="bg-surface/50 p-6 rounded-lg border border-soft-border">
                            <h3 class="text-lg font-bold text-on-surface mb-4 border-b border-soft-border pb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">menu_book</span> Directions
                            </h3>
                            @if($product->usage_instructions)
                            <div class="mb-4">
                                <strong class="text-on-surface block text-sm mb-1 uppercase tracking-wider text-primary">How to Use</strong>
                                <div class="text-on-surface-variant prose max-w-none">{!! $product->usage_instructions !!}</div>
                            </div>
                            @endif
                            @if($product->storage_instructions)
                            <div>
                                <strong class="text-on-surface block text-sm mb-1 uppercase tracking-wider text-primary">Storage</strong>
                                <div class="text-on-surface-variant prose max-w-none">{!! $product->storage_instructions !!}</div>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($product->ayurvedic_reference || $product->suitable_for)
                        <div class="bg-surface/50 p-6 rounded-lg border border-soft-border">
                            <h3 class="text-lg font-bold text-on-surface mb-4 border-b border-soft-border pb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">spa</span> Ayurvedic Info
                            </h3>
                            @if($product->ayurvedic_reference)
                            <div class="mb-4">
                                <strong class="text-on-surface block text-sm mb-1 uppercase tracking-wider text-primary">Ayurvedic Reference</strong>
                                <div class="text-on-surface-variant prose prose-sm max-w-none">{!! $product->ayurvedic_reference !!}</div>
                            </div>
                            @endif
                            @if($product->suitable_for)
                            <div>
                                <strong class="text-on-surface block text-sm mb-1 uppercase tracking-wider text-primary">Suitable For</strong>
                                <div class="text-on-surface-variant prose prose-sm max-w-none prose-li:text-on-surface-variant prose-ul:list-disc prose-ul:pl-5">{!! $product->suitable_for !!}</div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if($product->precautions)
                    <div>
                        <h3 class="text-lg font-bold text-on-surface mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-error text-[20px]">warning</span> Precautions
                        </h3>
                        <div class="p-4 bg-error/5 border border-error/20 rounded-lg text-error prose prose-sm max-w-none prose-p:text-error">
                            {!! $product->precautions !!}
                        </div>
                    </div>
                    @endif
                    
                    @if($product->disclaimer)
                    <div class="pt-4 border-t border-soft-border">
                        <strong class="text-on-surface block text-sm mb-1 uppercase tracking-wider">Disclaimer</strong>
                        <div class="text-sm italic text-on-surface-variant prose prose-sm max-w-none">
                            {!! $product->disclaimer !!}
                        </div>
                    </div>
                    @endif

                </div>
                @endif
                
            </div>
        </div>
    </section>

    <!-- Expert Notes Bento Grid -->
    {{-- 
    <section class="mb-20">
        <h2 class="font-display-lg text-3xl font-bold text-on-surface mb-8 text-center">Expert Validation</h2>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-8 bg-white border border-soft-border rounded-xl p-8 lg:p-12 hover-border-primary transition-colors flex flex-col justify-center shadow-sm">
                <span class="material-symbols-outlined text-5xl text-primary/30 mb-6 font-serif">format_quote</span>
                <p class="font-body-lg text-xl lg:text-2xl text-on-surface italic mb-8 leading-relaxed">
                    "This formulation represents the pinnacle of our research-driven approach. By combining ancient Ayurvedic wisdom with modern extraction techniques, we've achieved a product that delivers consistent, measurable support for your health without compromising on purity."
                </p>
                <div>
                    <h4 class="font-headline-md text-xl font-bold text-on-surface">Dr. Rajni Dubey</h4>
                    <p class="font-body-md text-base text-on-surface-variant">Expert Ayurvedic Vaidya & Formulation Specialist</p>
                </div>
            </div>
            <div class="md:col-span-4 bg-surface border border-soft-border rounded-xl p-8 flex flex-col items-center justify-center text-center hover-border-primary transition-colors shadow-sm">
                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-primary/20 shadow-md">
                    <!-- Placeholder doctor image -->
                    <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name=Dr+Rajni+Dubey&background=f2daff&color=7e00c9&size=256" alt="Dr. Rajni Dubey">
                </div>
                <ul class="text-left font-label-md text-sm font-medium text-on-surface-variant space-y-3 w-full">
                    <li class="flex items-center gap-3"><span class="material-symbols-outlined text-primary text-lg bg-primary/10 p-1.5 rounded-full">verified</span> Authentic Formulation</li>
                    <li class="flex items-center gap-3"><span class="material-symbols-outlined text-primary text-lg bg-primary/10 p-1.5 rounded-full">science</span> Research Based</li>
                    <li class="flex items-center gap-3"><span class="material-symbols-outlined text-primary text-lg bg-primary/10 p-1.5 rounded-full">fact_check</span> GMP Focused</li>
                </ul>
            </div>
        </div>
    </section>
    --}}
    
    <!-- Reviews Section -->
    <section class="mb-20 max-w-4xl mx-auto border-t border-soft-border pt-16">
        <div class="flex flex-col md:flex-row gap-12">
            <div class="md:w-1/3">
                <h3 class="text-3xl font-bold mb-2">Customer Reviews</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $avgRating)
                                <span class="material-symbols-outlined text-[28px] fill-current" style="font-variation-settings: 'FILL' 1;">star</span>
                            @else
                                <span class="material-symbols-outlined text-[28px]">star</span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-2xl font-bold text-on-surface">{{ number_format($avgRating, 1) }}</span>
                </div>
                <p class="text-on-surface-variant font-medium mb-8">Based on {{ $totalReviews }} reviews</p>
                
                @auth
                    <div x-data="{ openReviewForm: false }">
                        <button @click="openReviewForm = !openReviewForm" class="w-full border-2 border-primary text-primary font-bold py-3 rounded-lg hover:bg-primary hover:text-white transition-colors mb-4">Write a Review</button>
                        
                        <form x-show="openReviewForm" action="{{ route('review.store', $product->id) }}" method="POST" class="bg-surface p-6 rounded-xl border border-soft-border space-y-4 shadow-sm" x-transition>
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-on-surface mb-2">Your Rating</label>
                                <div class="flex gap-2 flex-row-reverse justify-end peer">
                                    <input type="radio" name="rating" id="star5" value="5" class="peer hidden"><label for="star5" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 text-3xl transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                                    <input type="radio" name="rating" id="star4" value="4" class="peer hidden"><label for="star4" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 peer-checked:~label:text-amber-400 text-3xl transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                                    <input type="radio" name="rating" id="star3" value="3" class="peer hidden"><label for="star3" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 text-3xl transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                                    <input type="radio" name="rating" id="star2" value="2" class="peer hidden"><label for="star2" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 text-3xl transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                                    <input type="radio" name="rating" id="star1" value="1" class="peer hidden" checked><label for="star1" class="material-symbols-outlined cursor-pointer text-amber-400 text-3xl transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-on-surface mb-1">Review Title</label>
                                <input type="text" name="title" required class="w-full border border-soft-border rounded-lg focus:ring-primary focus:border-primary px-4 py-2 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-on-surface mb-1">Your Review</label>
                                <textarea name="comment" rows="4" required class="w-full border border-soft-border rounded-lg focus:ring-primary focus:border-primary px-4 py-2 bg-white"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-primary/90 transition-colors">Submit Review</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block text-center w-full border-2 border-primary text-primary font-bold py-3 rounded-lg hover:bg-primary hover:text-white transition-colors">Log in to Review</a>
                @endauth
            </div>
            
            <div class="md:w-2/3 space-y-6">
                @forelse($product->reviews as $review)
                    <div class="bg-white p-6 rounded-xl border border-soft-border shadow-sm hover-border-primary transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center font-bold text-primary text-lg">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-on-surface text-lg">{{ $review->user->name }}</h4>
                                    <p class="text-xs text-on-surface-variant font-medium">{{ $review->created_at->format('F d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <span class="material-symbols-outlined text-[18px] fill-current" style="font-variation-settings: 'FILL' 1;">star</span>
                                    @else
                                        <span class="material-symbols-outlined text-[18px]">star</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <h5 class="font-bold text-on-surface mt-4 text-lg">{{ $review->title }}</h5>
                        <p class="text-on-surface-variant mt-2 text-base leading-relaxed">{{ $review->review }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 bg-surface rounded-xl border border-soft-border border-dashed">
                        <span class="material-symbols-outlined text-5xl text-outline mb-4">rate_review</span>
                        <h3 class="text-xl font-bold text-on-surface mb-2">No Reviews Yet</h3>
                        <p class="text-on-surface-variant">Be the first to share your experience with this formulation!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="border-t border-soft-border pt-16">
        <div class="flex justify-between items-end mb-8">
            <h2 class="text-3xl font-bold text-on-surface font-display">Complementary Care</h2>
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-primary font-medium hover:underline flex items-center gap-1 transition-colors">
                View All <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.show', $related->slug) }}" class="group bg-white border border-soft-border rounded-lg overflow-hidden transition-all duration-300 hover-border-primary flex flex-col h-full relative shadow-sm">
                    <div class="aspect-square bg-surface-container overflow-hidden relative">
                        @if($related->main_image)
                            <img src="{{ asset('storage/' . $related->main_image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-outline">image</span>
                            </div>
                        @endif
                        
                        @if($related->sale_price)
                            <div class="absolute top-3 left-3 bg-error/10 text-error font-label-sm text-xs font-bold px-2 py-1 rounded backdrop-blur-sm bg-white/90">
                                SALE
                            </div>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="text-xs font-bold text-primary mb-2 uppercase tracking-wider">{{ $related->category->name }}</div>
                        <h3 class="text-on-surface font-bold text-lg line-clamp-1 group-hover:text-primary transition-colors mb-2">{{ $related->name }}</h3>
                        
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-soft-border group-hover:border-primary/20 transition-colors">
                            <div class="flex flex-col">
                                @if($related->sale_price)
                                    <span class="font-bold text-on-surface text-lg">₹{{ number_format($related->sale_price, 2) }}</span>
                                    <span class="text-xs text-on-surface-variant line-through font-medium">₹{{ number_format($related->price, 2) }}</span>
                                @else
                                    <span class="font-bold text-on-surface text-lg">₹{{ number_format($related->price, 2) }}</span>
                                @endif
                            </div>
                            <div class="w-10 h-10 rounded border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 0;">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

</main>
@endsection
