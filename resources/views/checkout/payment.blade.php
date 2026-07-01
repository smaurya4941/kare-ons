@extends('layouts.app')

@section('title', 'Processing Payment - Kare Ons Herbal')

@section('content')
<div class="max-w-3xl mx-auto px-margin-desktop py-20 text-center min-h-[70vh] flex flex-col items-center justify-center">
    <div class="w-16 h-16 border-4 border-surface-container-high border-t-primary rounded-full animate-spin mb-8 mx-auto"></div>
    
    <h1 class="text-3xl font-display font-bold text-on-surface mb-4">Initializing Secure Payment...</h1>
    <p class="text-secondary mb-8">Please wait while we open the secure payment gateway. Do not refresh this page.</p>

    <!-- Hidden form for Razorpay Callback -->
    <form action="{{ route('checkout.callback') }}" method="POST" id="razorpay-form" class="hidden">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            "key": "{{ setting('razorpay_key') }}", 
            "amount": "{{ round($order->grand_total * 100) }}", 
            "currency": "INR",
            "name": "{{ setting('site_name', 'Kare Ons Herbal') }}",
            "description": "Payment for Order #{{ $order->order_number }}",
            "image": "{{ setting('logo') ? asset('storage/' . setting('logo')) : '' }}",
            "order_id": "{{ $order->payment->razorpay_order_id }}",
            "handler": function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpay-form').submit();
            },
            "prefill": {
                "name": "{{ $order->address->full_name ?? ($order->user->name ?? '') }}",
                "email": "{{ $order->user->email ?? '' }}",
                "contact": "{{ $order->address->phone ?? ($order->user->phone ?? '') }}"
            },
            "theme": {
                "color": "#1A5A3C"
            },
            "modal": {
                "ondismiss": function(){
                    // Redirect to orders if payment is dismissed so they can retry later
                    window.location.href = "{{ route('orders.index') }}";
                }
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    });
</script>
@endpush
@endsection
