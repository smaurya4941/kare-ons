<x-customer-layout>
    <x-slot name="title">My Wishlist</x-slot>

    @if($wishlists->isEmpty())
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-16 text-center">
            <div class="w-20 h-20 bg-surface-container mx-auto rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary text-3xl">favorite</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-2">Your wishlist is empty</h3>
            <p class="text-sm text-secondary mb-6">Save items you love to your wishlist and review them later.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-on-primary-fixed-variant transition">
                <span class="material-symbols-outlined text-[18px]">storefront</span>
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($wishlists as $item)
                <div class="bg-surface rounded-2xl border border-outline-variant overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                    <div class="relative aspect-[4/3] bg-surface-container overflow-hidden">
                        <a href="{{ route('product.show', $item->product->slug) }}">
                            @if($item->product->main_image)
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-outline text-4xl">image</span>
                                </div>
                            @endif
                        </a>
                        <form action="{{ route('wishlist.remove', $item->product_id) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors shadow-sm" title="Remove from Wishlist">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                    
                    <div class="p-5 flex flex-col flex-grow">
                        <a href="{{ route('product.show', $item->product->slug) }}" class="text-base font-bold text-on-surface hover:text-primary transition line-clamp-2 mb-2">{{ $item->product->name }}</a>
                        
                        <div class="mt-auto">
                            <div class="flex items-center gap-2 mb-4">
                                @if($item->product->sale_price)
                                    <span class="text-lg font-bold text-on-surface">₹{{ number_format($item->product->sale_price, 2) }}</span>
                                    <span class="text-sm text-secondary line-through">₹{{ number_format($item->product->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-on-surface">₹{{ number_format($item->product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary-container text-on-primary-container hover:bg-primary hover:text-white font-medium py-2.5 rounded-xl transition text-sm">
                                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-customer-layout>
