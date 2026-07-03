@extends('layouts.app')

@section('title', 'Your Cart - Kare Ons Herbal')

@section('content')
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8 min-h-[60vh]">
    <h1 class="text-2xl font-display font-bold text-herbal-deep mb-6">Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-xl border border-soft-border p-10 text-center shadow-sm">
            <div class="w-16 h-16 bg-herbal-light mx-auto rounded-full flex items-center justify-center mb-5">
                <span class="material-symbols-outlined text-[32px] text-herbal-accent">shopping_cart</span>
            </div>
            <h2 class="text-xl font-bold text-on-surface mb-2">Your cart is empty</h2>
            <p class="text-on-surface-variant text-sm max-w-md mx-auto mb-6">Looks like you haven't added anything yet. Discover our natural, Ayurvedic remedies.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:bg-primary/90 transition text-sm">
                <span class="material-symbols-outlined text-[20px]">storefront</span> Continue Shopping
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-xl border border-soft-border shadow-sm overflow-hidden">
                    <div class="hidden sm:grid grid-cols-12 gap-4 px-5 py-3 border-b border-soft-border text-xs font-semibold text-on-surface-variant uppercase tracking-wider">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-2 text-center">Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-right">Total</div>
                    </div>

                    <div class="divide-y divide-soft-border">
                        @foreach($cartItems as $item)
                            @php
                                $price = $item->product->sale_price ?? $item->product->price;
                                $lineTotal = $price * $item->quantity;
                            @endphp
                            <div class="p-4 flex flex-col sm:grid sm:grid-cols-12 gap-4 items-center">
                                <!-- Product Details -->
                                <div class="col-span-6 flex items-center gap-3 w-full">
                                    <div class="w-20 h-20 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-soft-border">
                                        @if($item->product->main_image)
                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-outline">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('product.show', $item->product->slug) }}" class="font-semibold text-on-surface hover:text-primary transition line-clamp-2 text-sm">
                                            {{ $item->product->name }}
                                        </a>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $item->product->category->name ?? '' }}</p>

                                        <!-- Mobile only Remove/Price -->
                                        <div class="sm:hidden mt-2 flex items-center justify-between">
                                            <span class="font-bold text-on-surface text-sm">₹{{ number_format($price, 2) }}</span>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-error text-xs font-medium hover:underline flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span> Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Price -->
                                <div class="hidden sm:block col-span-2 text-center">
                                    <span class="text-sm font-medium text-on-surface">₹{{ number_format($price, 2) }}</span>
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-2 flex justify-center w-full sm:w-auto">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-soft-border rounded-lg overflow-hidden" x-data="{ qty: {{ $item->quantity }} }">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" @click="if(qty > 1) qty--" class="px-2.5 py-1.5 bg-surface hover:bg-surface-container transition text-on-surface">
                                            <span class="material-symbols-outlined text-[16px]">remove</span>
                                        </button>
                                        <input type="number" name="quantity" x-model="qty" min="1" max="{{ $item->product->stock_quantity }}" class="w-10 text-center border-none focus:ring-0 text-on-surface p-0 py-1.5 bg-white text-sm" onchange="this.form.submit()">
                                        <button type="submit" @click="if(qty < {{ $item->product->stock_quantity }}) qty++" class="px-2.5 py-1.5 bg-surface hover:bg-surface-container transition text-on-surface">
                                            <span class="material-symbols-outlined text-[16px]">add</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Desktop Total & Remove -->
                                <div class="hidden sm:flex col-span-2 flex-col items-end justify-center">
                                    <span class="font-bold text-on-surface text-sm">₹{{ number_format($lineTotal, 2) }}</span>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-1.5">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-on-surface-variant hover:text-error transition" title="Remove Item">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
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
                <div class="bg-white rounded-xl border border-soft-border shadow-sm p-5 sticky top-20">
                    <h2 class="text-lg font-bold text-on-surface mb-4 font-display">Order Summary</h2>

                    <!-- Coupon hint (coupons are applied at checkout) -->
                    <div class="mb-4 border-b border-soft-border pb-4">
                        <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] text-herbal-accent">local_offer</span>
                            <span>Have a coupon? Apply it at checkout.</span>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 mb-4 text-sm">
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="font-medium text-on-surface">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-on-surface-variant">
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

                    <div class="border-t border-soft-border pt-3 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-on-surface">Total</span>
                            <span class="text-xl font-bold text-herbal-deep">₹{{ number_format($total, 2) }}</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-1 text-right">Inclusive of all taxes</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center gap-2 bg-primary text-white font-medium text-sm py-3 rounded-lg hover:bg-primary/90 transition shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                        Proceed to Checkout <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </a>

                    <!-- Trust Badges -->
                    <div class="mt-4 flex items-center justify-center gap-4 text-on-surface-variant text-xs">
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
