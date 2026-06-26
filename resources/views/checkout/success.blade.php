@extends('layouts.app')

@section('title', 'Order Placed - Kare Ons Herbal')

@section('content')
<div class="max-w-3xl mx-auto px-4 md:px-8 py-20 text-center min-h-[70vh] flex flex-col items-center justify-center">
    <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-8 mx-auto shadow-lg">
        <span class="material-symbols-outlined text-[56px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
    
    <h1 class="text-4xl font-display font-bold text-on-surface mb-4">Order Placed Successfully!</h1>
    <p class="text-lg text-secondary mb-8 max-w-md">Thank you for your purchase. Your order has been received and will be processed within 24 hours.</p>
    
    @if(session('order_number'))
    <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant max-w-md w-full mb-8 text-left shadow-sm">
        <h3 class="font-bold text-on-surface mb-4 border-b border-outline-variant pb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">receipt_long</span>
            Order Details
        </h3>
        <div class="flex justify-between mb-3 text-sm">
            <span class="text-secondary font-medium">Order Number</span>
            <span class="font-bold text-on-surface text-primary">#{{ session('order_number') }}</span>
        </div>
        <div class="flex justify-between mb-3 text-sm">
            <span class="text-secondary font-medium">Order Date</span>
            <span class="font-medium text-on-surface">{{ now()->format('M d, Y') }}</span>
        </div>
        <div class="flex justify-between mb-3 text-sm">
            <span class="text-secondary font-medium">Payment Method</span>
            <span class="font-medium text-on-surface">Cash on Delivery</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-secondary font-medium">Estimated Delivery</span>
            <span class="font-medium text-on-surface">5–7 Business Days</span>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 max-w-md w-full mb-8 text-left">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 text-[20px] mt-0.5">info</span>
            <div>
                <p class="text-sm font-semibold text-amber-800">Cash on Delivery Order</p>
                <p class="text-sm text-amber-700 mt-1">Please keep exact change ready at the time of delivery. Our delivery partner will contact you before arriving.</p>
            </div>
        </div>
    </div>
    @endif
    
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        @auth
            <a href="{{ route('dashboard') }}" class="bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface font-medium px-8 py-3 rounded-xl transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                View My Orders
            </a>
        @endauth
        <a href="{{ route('shop.index') }}" class="bg-primary text-white font-medium px-8 py-3 rounded-xl hover:bg-on-primary-fixed-variant transition flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">storefront</span>
            Continue Shopping
        </a>
    </div>
</div>
@endsection

