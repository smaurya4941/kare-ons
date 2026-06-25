@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name . ' - Kare Ons Herbal')

@section('content')
<div class="max-w-container-max mx-auto px-margin-desktop py-12">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-secondary mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-primary transition">Home</a></li>
            <li><span class="material-symbols-outlined text-[16px]">chevron_right</span></li>
            <li><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-primary transition">{{ $product->category->name }}</a></li>
            <li><span class="material-symbols-outlined text-[16px]">chevron_right</span></li>
            <li class="text-on-surface font-medium" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Top Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        
        <!-- Left: Image Gallery -->
        <div x-data="{ 
            activeImage: '{{ asset('storage/' . $product->main_image) }}',
            images: [
                '{{ asset('storage/' . $product->main_image) }}',
                @foreach($product->images as $image)
                    '{{ asset('storage/' . $image->image_path) }}',
                @endforeach
            ],
            zoomStyle: '',
            zoom(e) {
                const rect = e.target.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;
                this.zoomStyle = `transform-origin: ${xPercent}% ${yPercent}%; transform: scale(2);`;
            },
            resetZoom() {
                this.zoomStyle = 'transform-origin: center center; transform: scale(1);';
            }
        }">
            <!-- Main Image with Zoom -->
            <div class="bg-surface-container rounded-2xl overflow-hidden aspect-square border border-outline-variant mb-4 relative cursor-zoom-in"
                 @mousemove="zoom($event)" 
                 @mouseleave="resetZoom()">
                <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-200" :style="zoomStyle">
                @if($product->sale_price)
                    <div class="absolute top-4 left-4 bg-error text-white font-bold px-3 py-1.5 rounded-lg shadow-sm">
                        SALE
                    </div>
                @endif
            </div>

            <!-- Thumbnails -->
            <div class="grid grid-cols-5 gap-3">
                <button @click="activeImage = images[0]" 
                        class="aspect-square rounded-lg border-2 overflow-hidden bg-surface-container"
                        :class="activeImage === images[0] ? 'border-primary' : 'border-transparent hover:border-outline-variant'">
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image" class="w-full h-full object-cover">
                </button>
                @foreach($product->images as $index => $image)
                    <button @click="activeImage = images[{{ $index + 1 }}]" 
                            class="aspect-square rounded-lg border-2 overflow-hidden bg-surface-container"
                            :class="activeImage === images[{{ $index + 1 }}] ? 'border-primary' : 'border-transparent hover:border-outline-variant'">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery Image {{ $index + 1 }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Right: Product Info -->
        <div class="flex flex-col">
            <div class="mb-6">
                <h1 class="text-3xl sm:text-4xl font-display font-bold text-on-surface mb-2">{{ $product->name }}</h1>
                <p class="text-sm text-secondary uppercase tracking-wider font-medium mb-4">{{ $product->category->name }} | SKU: {{ $product->sku }}</p>
                
                <div class="flex items-center gap-2 mb-6">
                    <div class="flex text-amber-400">
                        @php
                            $avgRating = $product->reviews->avg('rating') ?? 5;
                            $totalReviews = $product->reviews->count();
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $avgRating)
                                <span class="material-symbols-outlined text-[20px] fill-current">star</span>
                            @else
                                <span class="material-symbols-outlined text-[20px]">star</span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-secondary">({{ $totalReviews }} Reviews)</span>
                </div>

                <div class="flex items-end gap-3 mb-6">
                    @if($product->sale_price)
                        <span class="text-3xl font-bold text-on-surface">₹{{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-lg text-secondary line-through mb-1">₹{{ number_format($product->price, 2) }}</span>
                        @php
                            $saved = $product->price - $product->sale_price;
                            $percent = round(($saved / $product->price) * 100);
                        @endphp
                        <span class="text-sm font-semibold text-error bg-error-container px-2 py-1 rounded mb-1">Save {{ $percent }}%</span>
                    @else
                        <span class="text-3xl font-bold text-on-surface">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <p class="text-body-md text-on-surface-variant mb-8 leading-relaxed">
                    {{ $product->short_description ?? Str::limit($product->description, 150) }}
                </p>
            </div>

            <!-- Add to Cart Form -->
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm mb-8">
                <!-- Stock Status -->
                <div class="mb-4 flex items-center gap-2 font-medium">
                    @if($product->stock_quantity > 10)
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-700">In Stock</span>
                    @elseif($product->stock_quantity > 0)
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-amber-700">Limited Stock (Only {{ $product->stock_quantity }} left)</span>
                    @else
                        <span class="w-3 h-3 rounded-full bg-error"></span>
                        <span class="text-error">Out of Stock</span>
                    @endif
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center gap-4">
                        <label for="quantity" class="font-medium text-on-surface">Quantity</label>
                        <div class="flex items-center border border-outline-variant rounded-lg overflow-hidden" x-data="{ qty: 1 }">
                            <button type="button" @click="if(qty > 1) qty--" class="px-4 py-2 bg-surface hover:bg-surface-container transition text-on-surface">
                                <span class="material-symbols-outlined text-[18px]">remove</span>
                            </button>
                            <input type="number" name="quantity" id="quantity" x-model="qty" min="1" max="{{ $product->stock_quantity > 0 ? $product->stock_quantity : 1 }}" class="w-16 text-center border-none focus:ring-0 text-on-surface p-0 py-2 bg-white" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <button type="button" @click="if(qty < {{ $product->stock_quantity }}) qty++" class="px-4 py-2 bg-surface hover:bg-surface-container transition text-on-surface">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <button type="submit" name="action" value="cart" class="flex-1 bg-surface-container hover:bg-surface-container-high text-on-surface border border-outline-variant font-medium py-3 px-6 rounded-xl transition flex justify-center items-center gap-2" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span> Add to Cart
                        </button>
                        <button type="submit" name="action" value="buy" class="flex-1 bg-primary hover:bg-on-primary-fixed-variant text-white font-medium py-3 px-6 rounded-xl transition flex justify-center items-center gap-2 shadow-sm" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <span class="material-symbols-outlined text-[20px]">bolt</span> Buy Now
                        </button>
                    </div>
                </form>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-3 text-secondary">
                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">eco</span>
                    </div>
                    <span>100% Ayurvedic</span>
                </div>
                <div class="flex items-center gap-3 text-secondary">
                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">shield</span>
                    </div>
                    <span>Secure Payments</span>
                </div>
                <div class="flex items-center gap-3 text-secondary">
                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">local_shipping</span>
                    </div>
                    <span>Fast Delivery</span>
                </div>
                <div class="flex items-center gap-3 text-secondary">
                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                    <span>Quality Assured</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Tabs -->
    <div class="mb-16" x-data="{ activeTab: 'description' }">
        <div class="border-b border-outline-variant flex overflow-x-auto no-scrollbar mb-8">
            <button @click="activeTab = 'description'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'description' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-on-surface'">Description</button>
            
            @if($product->ingredients)
            <button @click="activeTab = 'ingredients'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'ingredients' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-on-surface'">Ingredients</button>
            @endif
            
            @if($product->benefits)
            <button @click="activeTab = 'benefits'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'benefits' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-on-surface'">Health Benefits</button>
            @endif
            
            @if($product->usage_instructions || $product->storage_instructions)
            <button @click="activeTab = 'directions'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'directions' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-on-surface'">How to Use & Store</button>
            @endif
            
            @if($product->precautions)
            <button @click="activeTab = 'precautions'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'precautions' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-on-surface'">Precautions</button>
            @endif
            
            <button @click="activeTab = 'reviews'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-on-surface'">Reviews ({{ $totalReviews }})</button>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm text-body-md text-on-surface leading-relaxed">
            <!-- Description Tab -->
            <div x-show="activeTab === 'description'" x-transition.opacity>
                <h3 class="text-xl font-semibold mb-4">Overview</h3>
                <div class="prose max-w-none prose-p:text-on-surface-variant prose-headings:text-on-surface">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Ingredients Tab -->
            @if($product->ingredients)
            <div x-show="activeTab === 'ingredients'" x-transition.opacity style="display: none;">
                <h3 class="text-xl font-semibold mb-4">What's Inside</h3>
                <div class="prose max-w-none prose-p:text-on-surface-variant">
                    {!! nl2br(e($product->ingredients)) !!}
                </div>
            </div>
            @endif

            <!-- Benefits Tab -->
            @if($product->benefits)
            <div x-show="activeTab === 'benefits'" x-transition.opacity style="display: none;">
                <h3 class="text-xl font-semibold mb-4">Key Benefits</h3>
                <div class="prose max-w-none prose-p:text-on-surface-variant">
                    {!! nl2br(e($product->benefits)) !!}
                </div>
            </div>
            @endif

            <!-- Directions Tab -->
            @if($product->usage_instructions || $product->storage_instructions)
            <div x-show="activeTab === 'directions'" x-transition.opacity style="display: none;">
                @if($product->usage_instructions)
                <h3 class="text-xl font-semibold mb-4">Directions for Use</h3>
                <div class="prose max-w-none prose-p:text-on-surface-variant mb-8">
                    {!! nl2br(e($product->usage_instructions)) !!}
                </div>
                @endif
                
                @if($product->storage_instructions)
                <h3 class="text-xl font-semibold mb-4">Storage Instructions</h3>
                <div class="prose max-w-none prose-p:text-on-surface-variant">
                    {!! nl2br(e($product->storage_instructions)) !!}
                </div>
                @endif
            </div>
            @endif

            <!-- Precautions Tab -->
            @if($product->precautions)
            <div x-show="activeTab === 'precautions'" x-transition.opacity style="display: none;">
                <h3 class="text-xl font-semibold mb-4 text-error">Precautions & Warnings</h3>
                <div class="prose max-w-none prose-p:text-on-surface-variant">
                    {!! nl2br(e($product->precautions)) !!}
                </div>
            </div>
            @endif

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" x-transition.opacity style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="col-span-1">
                        <h3 class="text-2xl font-bold mb-2">Customer Reviews</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avgRating)
                                        <span class="material-symbols-outlined text-[24px] fill-current">star</span>
                                    @else
                                        <span class="material-symbols-outlined text-[24px]">star</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xl font-semibold">{{ number_format($avgRating, 1) }} out of 5</span>
                        </div>
                        <p class="text-secondary mb-6">Based on {{ $totalReviews }} reviews</p>
                        
                        @auth
                            <div x-data="{ openReviewForm: false }">
                                <button @click="openReviewForm = !openReviewForm" class="w-full border-2 border-primary text-primary font-medium py-2 rounded-lg hover:bg-primary hover:text-white transition mb-4">Write a Review</button>
                                
                                <form x-show="openReviewForm" action="{{ route('review.store', $product->id) }}" method="POST" class="bg-surface p-4 rounded-xl border border-outline-variant space-y-4" x-transition>
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-on-surface mb-1">Rating</label>
                                        <div class="flex gap-2 flex-row-reverse justify-end peer">
                                            <input type="radio" name="rating" id="star5" value="5" class="peer hidden"><label for="star5" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 text-2xl">star</label>
                                            <input type="radio" name="rating" id="star4" value="4" class="peer hidden"><label for="star4" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 peer-checked:~label:text-amber-400 text-2xl">star</label>
                                            <input type="radio" name="rating" id="star3" value="3" class="peer hidden"><label for="star3" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 text-2xl">star</label>
                                            <input type="radio" name="rating" id="star2" value="2" class="peer hidden"><label for="star2" class="material-symbols-outlined cursor-pointer text-outline hover:text-amber-400 peer-checked:text-amber-400 text-2xl">star</label>
                                            <input type="radio" name="rating" id="star1" value="1" class="peer hidden" checked><label for="star1" class="material-symbols-outlined cursor-pointer text-amber-400 text-2xl">star</label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-on-surface mb-1">Title</label>
                                        <input type="text" name="title" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-on-surface mb-1">Your Review</label>
                                        <textarea name="comment" rows="3" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary"></textarea>
                                    </div>
                                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-on-primary-fixed-variant transition">Submit Review</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="block text-center w-full border-2 border-primary text-primary font-medium py-2 rounded-lg hover:bg-primary hover:text-white transition">Log in to Review</a>
                        @endauth
                    </div>
                    
                    <div class="col-span-1 md:col-span-2 space-y-6">
                        @forelse($product->reviews as $review)
                            <div class="border-b border-outline-variant pb-6 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-surface-container rounded-full flex items-center justify-center font-bold text-primary">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-on-surface">{{ $review->user->name }}</h4>
                                            <p class="text-xs text-secondary">{{ $review->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex text-amber-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                            @else
                                                <span class="material-symbols-outlined text-[16px]">star</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <h5 class="font-medium text-on-surface mt-3">{{ $review->title }}</h5>
                                <p class="text-on-surface-variant mt-1 text-sm">{{ $review->review }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-secondary">No reviews yet. Be the first to review this product!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mb-8">
        <div class="flex justify-between items-end border-b border-outline-variant pb-4 mb-8">
            <h2 class="text-2xl font-bold text-on-surface font-display">Related Products</h2>
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-primary font-medium hover:underline flex items-center gap-1">
                View Category <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.show', $related->slug) }}" class="group block border border-outline-variant rounded-xl overflow-hidden hover:border-primary transition-colors bg-surface">
                    <div class="aspect-[4/3] bg-surface-container relative overflow-hidden">
                        @if($related->main_image)
                            <img src="{{ asset('storage/' . $related->main_image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-outline">image</span>
                            </div>
                        @endif
                        
                        @if($related->sale_price)
                            <div class="absolute top-3 left-3 bg-error text-white text-xs font-bold px-2 py-1 rounded">
                                SALE
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="text-xs font-medium text-primary mb-2">{{ $related->category->name }}</div>
                        <h3 class="text-on-surface font-semibold text-lg line-clamp-1 group-hover:text-primary transition-colors">{{ $related->name }}</h3>
                        
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-outline-variant">
                            <div class="flex items-center gap-2">
                                @if($related->sale_price)
                                    <span class="font-bold text-on-surface">₹{{ number_format($related->sale_price, 2) }}</span>
                                    <span class="text-xs text-secondary line-through">₹{{ number_format($related->price, 2) }}</span>
                                @else
                                    <span class="font-bold text-on-surface">₹{{ number_format($related->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
