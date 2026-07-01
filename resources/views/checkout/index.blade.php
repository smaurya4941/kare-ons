@extends('layouts.app')

@section('title', 'Checkout - Kare Ons Herbal')

@section('content')
<div class="max-w-container-max mx-auto px-margin-desktop py-12 bg-surface-container-lowest min-h-screen">
    <h1 class="text-3xl font-display font-bold text-on-surface mb-8">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST" class="flex flex-col lg:flex-row gap-10">
        @csrf
        
        @if(session('error'))
            <div class="lg:col-span-full mb-6 bg-error-container text-on-error-container p-4 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if(session('success'))
            <div class="lg:col-span-full mb-6 bg-secondary-container text-on-secondary-container p-4 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if($errors->any())
            <div class="lg:col-span-full mb-6 bg-error-container text-on-error-container p-4 rounded-lg flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5">warning</span>
                <div>
                    <h4 class="font-bold mb-1">Please fix the following errors:</h4>
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
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

                @auth
                    @if($addresses->isNotEmpty())
                        <div x-data="{
                            selectedAddress: '{{ $addresses->where('is_default', true)->first()?->id ?? '' }}',
                            addresses: {{ Js::from($addresses->keyBy('id')) }},
                            fillAddress() {
                                if(this.selectedAddress && this.addresses[this.selectedAddress]) {
                                    const addr = this.addresses[this.selectedAddress];
                                    document.getElementById('full_name').value = addr.full_name;
                                    document.getElementById('phone').value = addr.phone;
                                    document.getElementById('address_line_1').value = addr.address_line_1;
                                    document.getElementById('city').value = addr.city;
                                    document.getElementById('state').value = addr.state;
                                    document.getElementById('postal_code').value = addr.postal_code;
                                }
                            }
                        }" x-init="fillAddress()" class="mb-6">
                            <label class="block text-sm font-medium text-secondary mb-2">Use a Saved Address</label>
                            <select x-model="selectedAddress" @change="fillAddress()" class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary bg-surface-container-lowest text-on-surface">
                                <option value="">-- Or enter a new address below --</option>
                                @foreach($addresses as $addr)
                                    <option value="{{ $addr->id }}">{{ $addr->full_name }} ({{ $addr->postal_code }}) - {{ $addr->address_line_1 }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endauth

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
                    @foreach($paymentMethods as $index => $pm)
                    <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer hover:bg-surface-container transition {{ $index == 0 ? 'border-primary bg-primary-fixed' : 'border-outline-variant' }} has-[:checked]:border-primary has-[:checked]:bg-primary-fixed">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="{{ $pm->code }}" class="w-5 h-5 text-primary focus:ring-primary border-outline-variant" {{ $index == 0 ? 'checked' : '' }}>
                            <div>
                                <h3 class="font-semibold text-on-surface flex items-center gap-2">
                                    {{ $pm->name }}
                                </h3>
                                @if($pm->instructions)
                                <p class="text-xs text-secondary mt-1">{{ $pm->instructions }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[32px] {{ $index == 0 ? 'text-primary' : 'text-outline has-[:checked]:text-primary' }}">
                            {{ $pm->code === 'cod' ? 'local_shipping' : ($pm->code === 'razorpay' ? 'account_balance' : 'account_balance_wallet') }}
                        </span>
                    </label>
                    @endforeach
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

                <div x-data="{
                        code: '',
                        applied: '',
                        discount: 0,
                        loading: false,
                        error: '',
                        message: '',
                        subtotal: {{ (float) $subtotal }},
                        tax: {{ (float) $taxAmount }},
                        shipping: {{ (float) $shipping }},
                        get grandTotal() { return Math.max(0, this.subtotal + this.tax + this.shipping - this.discount); },
                        fmt(n) { return Number(n).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },
                        async apply() {
                            if (!this.code.trim()) { this.error = 'Please enter a coupon code.'; return; }
                            this.loading = true; this.error = ''; this.message = '';
                            try {
                                const res = await fetch('{{ route('coupon.apply') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ code: this.code, subtotal: this.subtotal })
                                });
                                const data = await res.json();
                                if (res.ok && data.success) {
                                    this.discount = parseFloat(data.discount) || 0;
                                    this.applied = data.code;
                                    this.message = data.message;
                                } else {
                                    this.discount = 0; this.applied = '';
                                    this.error = data.message || 'Invalid coupon code.';
                                }
                            } catch (e) {
                                this.discount = 0; this.applied = '';
                                this.error = 'Something went wrong. Please try again.';
                            }
                            this.loading = false;
                        },
                        remove() { this.discount = 0; this.applied = ''; this.code = ''; this.error = ''; this.message = ''; }
                     }">

                    {{-- Hidden field submitted with the checkout form; server re-validates the coupon --}}
                    <input type="hidden" name="coupon_code" :value="applied">

                    <!-- Coupon -->
                    <div class="mb-6 border-t border-outline-variant pt-4">
                        <label class="block text-sm font-medium text-secondary mb-2">Have a coupon code?</label>
                        <div x-show="!applied" class="flex gap-2">
                            <input type="text" x-model="code" @keydown.enter.prevent="apply()" placeholder="Enter code"
                                   class="flex-1 border-outline-variant rounded-lg focus:ring-primary focus:border-primary px-4 py-2 uppercase text-sm">
                            <button type="button" @click="apply()" :disabled="loading"
                                    class="bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-medium transition disabled:opacity-50">
                                <span x-show="!loading">Apply</span>
                                <span x-show="loading">...</span>
                            </button>
                        </div>
                        <div x-show="applied" x-cloak class="flex items-center justify-between bg-secondary-container/40 border border-secondary rounded-lg px-4 py-2">
                            <span class="text-sm font-medium text-on-surface">Applied: <span class="font-bold font-mono" x-text="applied"></span></span>
                            <button type="button" @click="remove()" class="text-error text-sm font-medium hover:underline">Remove</button>
                        </div>
                        <p x-show="error" x-cloak class="text-error text-xs mt-2" x-text="error"></p>
                        <p x-show="message && applied" x-cloak class="text-secondary text-xs mt-2" x-text="message"></p>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between items-center text-secondary">
                            <span>Subtotal</span>
                            <span class="font-medium text-on-surface">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($taxAmount > 0)
                        <div class="flex justify-between items-center text-secondary">
                            <span>Tax (GST)</span>
                            <span class="font-medium text-on-surface">₹{{ number_format($taxAmount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center text-secondary">
                            <span>Shipping</span>
                            @if($shipping == 0)
                                <span class="font-medium text-emerald-600">Free</span>
                            @else
                                <span class="font-medium text-on-surface">₹{{ number_format($shipping, 2) }}</span>
                            @endif
                        </div>
                        <div x-show="discount > 0" x-cloak class="flex justify-between items-center text-error">
                            <span>Discount</span>
                            <span class="font-medium">-₹<span x-text="fmt(discount)"></span></span>
                        </div>
                    </div>

                    <div class="border-t border-outline-variant pt-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-on-surface">Total</span>
                            <span class="text-2xl font-bold text-on-surface">₹<span x-text="fmt(grandTotal)"></span></span>
                        </div>
                        <p class="text-xs text-secondary mt-1 text-right">Shipping is finalised from your PIN code on order placement.</p>
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary text-white font-medium py-4 rounded-xl hover:bg-on-primary-fixed-variant transition shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">lock</span> Place Order
                </button>
            </div>
        </div>
    </form>
</div>

<style>
[x-cloak] { display: none !important; }
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
