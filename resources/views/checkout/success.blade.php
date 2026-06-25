@extends('layouts.app')

@section('title', 'Order Success - Kare Ons Herbal')

@section('content')
<div class="max-w-3xl mx-auto px-margin-desktop py-20 text-center min-h-[70vh] flex flex-col items-center justify-center">
    <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-8 mx-auto">
        <span class="material-symbols-outlined text-[48px]">check_circle</span>
    </div>
    
    <h1 class="text-4xl font-display font-bold text-on-surface mb-4">Order Placed Successfully!</h1>
    <p class="text-lg text-secondary mb-8">Thank you for your purchase. Your order has been received and is being processed.</p>
    
    <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant max-w-md w-full mb-8 text-left">
        <h3 class="font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Order Details</h3>
        <div class="flex justify-between mb-2">
            <span class="text-secondary">Order Number:</span>
            <span class="font-medium text-on-surface">#ORD-{{ rand(100000, 999999) }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="text-secondary">Date:</span>
            <span class="font-medium text-on-surface">{{ now()->format('M d, Y') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-secondary">Payment Method:</span>
            <span class="font-medium text-on-surface">Cash on Delivery</span>
        </div>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        @auth
            <a href="{{ route('dashboard') }}" class="bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface font-medium px-8 py-3 rounded-xl transition">
                View Order History
            </a>
        @endauth
        <a href="{{ route('shop.index') }}" class="bg-primary text-white font-medium px-8 py-3 rounded-xl hover:bg-on-primary-fixed-variant transition">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
