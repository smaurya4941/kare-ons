@extends('layouts.app')

@section('title', 'Checkout - Kare Ons Herbal')

@section('content')
<div class="max-w-container-max mx-auto px-margin-desktop py-12 bg-surface-container-lowest min-h-screen">
    <h1 class="text-3xl font-display font-bold text-on-surface mb-8">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST" class="flex flex-col lg:flex-row gap-10">
        @csrf
        
        <div class="lg:w-2/3 space-y-8">
            <!-- Customer Information -->
            <div class="bg-surface rounded-2xl border border-outline-variant p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6 border-b border-outline-variant pb-4">
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">1</div>
                    <h2 class="text-xl font-bold text-on-surface">Delivery Details</h2>
                </div>

                @guest
                    <div class="mb-6 p-4 bg-surface-container rounded-lg flex items-center justify-between border border-outline-variant">
                        <div>
                            <h3 class="font-semibold text-on-surface">Already have an account?</h3>
                            <p class="text-sm text-secondary">Log in for faster checkout.</p>
                        </div>
                        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Log in</a>
                    </div>
                @endguest

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-secondary mb-1">Full Name <span class="text-error">*</span></label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', Auth::user()->name ?? '') }}" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-secondary mb-1">Phone Number <span class="text-error">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address_line_1" class="block text-sm font-medium text-secondary mb-1">Street Address <span class="text-error">*</span></label>
                        <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1') }}" required placeholder="House number and street name" class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-secondary mb-1">Town / City <span class="text-error">*</span></label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-secondary mb-1">State <span class="text-error">*</span></label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-secondary mb-1">PIN Code <span class="text-error">*</span></label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-surface rounded-2xl border border-outline-variant p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6 border-b border-outline-variant pb-4">
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">2</div>
                    <h2 class="text-xl font-bold text-on-surface">Payment Method</h2>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-4 border border-outline-variant rounded-xl cursor-pointer hover:bg-surface-container transition has-[:checked]:border-primary has-[:checked]:bg-primary-fixed">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="razorpay" class="w-5 h-5 text-primary focus:ring-primary border-outline-variant" checked>
                            <div>
                                <h3 class="font-semibold text-on-surface">Razorpay (Cards, UPI, NetBanking)</h3>
                                <p class="text-xs text-secondary mt-1">Secure online payment</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-primary text-[32px]">account_balance</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border border-outline-variant rounded-xl cursor-pointer hover:bg-surface-container transition has-[:checked]:border-primary has-[:checked]:bg-primary-fixed">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="cod" class="w-5 h-5 text-primary focus:ring-primary border-outline-variant">
                            <div>
                                <h3 class="font-semibold text-on-surface">Cash on Delivery</h3>
                                <p class="text-xs text-secondary mt-1">Pay when you receive the order</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-primary text-[32px]">local_shipping</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
            <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm p-8 sticky top-24">
                <h2 class="text-xl font-bold text-on-surface mb-6 border-b border-outline-variant pb-4">Order Summary</h2>

                <div class="space-y-4 mb-6 max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($cartItems as $item)
                        @php
                            $price = $item->product->sale_price ?? $item->product->price;
                        @endphp
                        <div class="flex gap-4">
                            <div class="w-16 h-16 bg-surface-container rounded border border-outline-variant overflow-hidden flex-shrink-0">
                                @if($item->product->main_image)
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-outline text-[16px]">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-on-surface line-clamp-2">{{ $item->product->name }}</h4>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-secondary">Qty: {{ $item->quantity }}</span>
                                    <span class="text-sm font-bold text-on-surface">₹{{ number_format($price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="space-y-3 mb-6 text-sm border-t border-outline-variant pt-4">
                    <div class="flex justify-between items-center text-secondary">
                        <span>Subtotal</span>
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
                </div>

                <div class="border-t border-outline-variant pt-4 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-on-surface">Total</span>
                        <span class="text-2xl font-bold text-on-surface">₹{{ number_format($total, 2) }}</span>
                    </div>
                    <p class="text-xs text-secondary mt-1 text-right">Inclusive of all taxes</p>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary text-white font-medium py-4 rounded-xl hover:bg-on-primary-fixed-variant transition shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">lock</span> Place Order
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #c6c6cd;
  border-radius: 4px;
}
</style>
@endsection
