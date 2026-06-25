@extends('layouts.app')

@section('title', 'Processing Payment - Kare Ons Herbal')

@section('content')
<div class="max-w-3xl mx-auto px-margin-desktop py-20 text-center min-h-[70vh] flex flex-col items-center justify-center">
    <!-- Simulated Loading Spinner -->
    <div class="w-16 h-16 border-4 border-surface-container-high border-t-primary rounded-full animate-spin mb-8 mx-auto"></div>
    
    <h1 class="text-3xl font-display font-bold text-on-surface mb-4">Initializing Razorpay...</h1>
    <p class="text-secondary mb-8">Please wait while we redirect you to the secure payment gateway. Do not refresh this page.</p>
    
    <!-- Phase 12 Placeholder Note -->
    <div class="bg-amber-50 text-amber-800 p-6 rounded-xl border border-amber-200 max-w-md w-full mt-4 text-left text-sm">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-amber-500">info</span>
            <div>
                <strong class="block mb-1">Developer Note (Phase 12)</strong>
                This is a placeholder for the Razorpay integration. In production, this view will automatically trigger the Razorpay checkout modal via JavaScript, process the payment, and redirect to the success callback. For now, you can proceed via Cash on Delivery.
                <div class="mt-4">
                    <a href="{{ route('checkout.index') }}" class="font-medium underline hover:text-amber-900">Return to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
