@extends('layouts.app')

@section('title', 'Your Cart - Kare Ons Herbal')

@section('content')
<div class="max-w-container-max mx-auto px-margin-desktop py-12 min-h-[60vh]">
    <h1 class="text-3xl font-display font-bold text-on-surface mb-8">Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-surface-container mx-auto rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[40px] text-secondary">shopping_cart</span>
            </div>
            <h2 class="text-2xl font-bold text-on-surface mb-3">Your cart is empty</h2>
            <p class="text-secondary max-w-md mx-auto mb-8">Looks like you haven't added anything to your cart yet. Discover our natural, Ayurvedic remedies.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-on-primary-fixed-variant transition">
                <span class="material-symbols-outlined text-[20px]">storefront</span> Continue Shopping
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="hidden sm:grid grid-cols-12 gap-4 p-6 border-b border-outline-variant bg-surface-container-lowest text-sm font-semibold text-secondary uppercase tracking-wider">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-2 text-center">Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-right">Total</div>
                    </div>

                    <div class="divide-y divide-outline-variant">
                        @foreach($cartItems as $item)
                            @php
                                $price = $item->product->sale_price ?? $item->product->price;
                                $lineTotal = $price * $item->quantity;
                            @endphp
                            <div class="p-6 flex flex-col sm:grid sm:grid-cols-12 gap-4 items-center">
                                <!-- Product Details -->
                                <div class="col-span-6 flex items-center gap-4 w-full">
                                    <div class="w-24 h-24 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant">
                                        @if($item->product->main_image)
                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-outline">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <a href="{{ route('product.show', $item->product->slug) }}" class="font-semibold text-on-surface hover:text-primary transition line-clamp-2 text-lg">
                                            {{ $item->product->name }}
                                        </a>
                                        <p class="text-sm text-secondary mt-1">{{ $item->product->category->name ?? '' }}</p>
                                        
                                        <!-- Mobile only Remove/Price -->
                                        <div class="sm:hidden mt-3 flex items-center justify-between">
                                            <span class="font-bold text-on-surface">₹{{ number_format($price, 2) }}</span>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-error text-sm font-medium hover:underline flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span> Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Price -->
                                <div class="hidden sm:block col-span-2 text-center">
                                    <span class="font-medium text-on-surface">₹{{ number_format($price, 2) }}</span>
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-2 flex justify-center w-full sm:w-auto mt-4 sm:mt-0">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-outline-variant rounded-lg overflow-hidden" x-data="{ qty: {{ $item->quantity }} }">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" @click="if(qty > 1) qty--" class="px-3 py-1.5 bg-surface hover:bg-surface-container transition text-on-surface">
                                            <span class="material-symbols-outlined text-[16px]">remove</span>
                                        </button>
                                        <input type="number" name="quantity" x-model="qty" min="1" max="{{ $item->product->stock_quantity }}" class="w-12 text-center border-none focus:ring-0 text-on-surface p-0 py-1.5 bg-white text-sm" onchange="this.form.submit()">
                                        <button type="submit" @click="if(qty < {{ $item->product->stock_quantity }}) qty++" class="px-3 py-1.5 bg-surface hover:bg-surface-container transition text-on-surface">
                                            <span class="material-symbols-outlined text-[16px]">add</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Desktop Total & Remove -->
                                <div class="hidden sm:flex col-span-2 flex-col items-end justify-center">
                                    <span class="font-bold text-on-surface text-lg">₹{{ number_format($lineTotal, 2) }}</span>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-secondary hover:text-error transition" title="Remove Item">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-on-surface mb-6 font-display">Order Summary</h2>

                    <!-- Coupon hint (coupons are applied at checkout) -->
                    <div class="mb-6 border-b border-outline-variant pb-6">
                        <div class="flex items-center gap-2 text-sm text-secondary">
                            <span class="material-symbols-outlined text-[18px]">local_offer</span>
                            <span>Have a coupon? Apply it at checkout.</span>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-4 mb-6 text-sm">
                        <div class="flex justify-between items-center text-secondary">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="font-medium text-on-surface">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-secondary">
                            <span>Shipping</span>
                            @if($shipping == 0)
                                <span class="font-medium text-emerald-600">Free</span>
                            @else
                                <span class="font-medium text-on-surface">₹{{ number_format($shipping, 2) }}</span>
                            @endif
                        </div>
                        @if($discount > 0)
                        <div class="flex justify-between items-center text-error">
                            <span>Discount</span>
                            <span class="font-medium">-₹{{ number_format($discount, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="border-t border-outline-variant pt-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-on-surface">Total</span>
                            <span class="text-2xl font-bold text-on-surface">₹{{ number_format($total, 2) }}</span>
                        </div>
                        <p class="text-xs text-secondary mt-1 text-right">Inclusive of all taxes</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center gap-2 bg-primary text-white font-medium text-lg py-4 rounded-xl hover:bg-on-primary-fixed-variant transition shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                        Proceed to Checkout <span class="material-symbols-outlined">arrow_forward</span>
                    </a>

                    <!-- Trust Badges -->
                    <div class="mt-6 flex items-center justify-center gap-4 text-secondary text-xs">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">lock</span> Secure Checkout
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">local_shipping</span> Fast Delivery
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
