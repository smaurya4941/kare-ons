<x-customer-layout>
    <x-slot name="title">Order #{{ $order->order_number }}</x-slot>

    <div class="flex-1 space-y-6">
        {{-- Back Button & Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-on-surface">Order Details</h1>
                <p class="text-sm text-secondary">#{{ $order->order_number }} • Placed on {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Delivery Address & Order Items --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Delivery Address --}}
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-6">
                    <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">location_on</span>
                        Delivery Address
                    </h2>
                    @if($order->address)
                        <div class="text-on-surface">
                            <p class="font-semibold text-lg">{{ $order->address->full_name }}</p>
                            <p class="text-secondary mt-1 leading-relaxed">
                                {{ $order->address->address_line_1 }}<br>
                                @if($order->address->address_line_2) {{ $order->address->address_line_2 }}<br> @endif
                                {{ $order->address->city }}, {{ $order->address->state }} - <span class="font-medium text-on-surface">{{ $order->address->postal_code }}</span><br>
                                {{ $order->address->country }}
                            </p>
                            <p class="font-medium mt-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-secondary">call</span>
                                {{ $order->address->phone }}
                            </p>
                        </div>
                    @else
                        <p class="text-secondary italic">No address recorded for this order.</p>
                    @endif
                </div>

                {{-- Order Items --}}
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-outline-variant bg-surface-container/40">
                        <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[22px]">inventory_2</span>
                            Products
                        </h2>
                    </div>
                    <div class="divide-y divide-outline-variant">
                        @foreach($order->items as $item)
                            <div class="p-6 flex flex-col sm:flex-row gap-6">
                                <div class="w-24 h-24 bg-surface-container rounded-lg border border-outline-variant overflow-hidden flex-shrink-0">
                                    @if($item->product && $item->product->main_image)
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-outline text-[32px]">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" class="text-lg font-bold text-on-surface hover:text-primary transition-colors line-clamp-2 mb-1">
                                        {{ $item->product_name }}
                                    </a>
                                    <p class="text-sm text-secondary mb-3">Seller: Kare Ons Herbal</p>
                                    
                                    <div class="flex items-end justify-between">
                                        <div>
                                            <p class="text-xs text-secondary mb-1">Price Details</p>
                                            <div class="flex items-center gap-3">
                                                <p class="text-xl font-bold text-on-surface">₹{{ number_format($item->price, 2) }}</p>
                                                <span class="text-sm text-secondary font-medium">Qty: {{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                        @if($order->order_status === 'delivered' && $item->product)
                                            <a href="{{ route('product.show', $item->product->slug) }}#reviews" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:underline">
                                                <span class="material-symbols-outlined text-[18px]">rate_review</span>
                                                Rate & Review
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column: Order Tracker & Summary --}}
            <div class="space-y-6">
                
                {{-- Track Order --}}
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-6">
                    <h2 class="text-lg font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">local_shipping</span>
                        Track Order
                    </h2>
                    
                    @php
                        $statuses = ['pending', 'confirmed', 'packed', 'shipped', 'delivered'];
                        $currentStatusIndex = array_search($order->order_status, $statuses);
                        
                        // If cancelled or returned, we show a different flow
                        $isCancelled = $order->order_status === 'cancelled';
                        $isReturned = $order->order_status === 'returned';
                    @endphp

                    @if($isCancelled)
                        <div class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-100 flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500">cancel</span>
                            <div>
                                <h3 class="font-bold">Order Cancelled</h3>
                                <p class="text-sm mt-1">This order has been cancelled. If you already paid, the refund will be processed within 5-7 business days.</p>
                            </div>
                        </div>
                    @elseif($isReturned)
                        <div class="p-4 bg-amber-50 text-amber-800 rounded-lg border border-amber-100 flex items-start gap-3">
                            <span class="material-symbols-outlined text-amber-500">assignment_return</span>
                            <div>
                                <h3 class="font-bold">Order Returned</h3>
                                <p class="text-sm mt-1">This order has been marked as returned. Refund status: {{ ucfirst($order->refund_status ?? 'pending') }}.</p>
                            </div>
                        </div>
                    @else
                        <div class="relative pl-6 space-y-8">
                            <!-- Vertical Line -->
                            <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-outline-variant"></div>

                            @foreach($statuses as $index => $status)
                                @php
                                    $isCompleted = $index <= $currentStatusIndex;
                                    $isCurrent = $index === $currentStatusIndex;
                                    
                                    // Find timeline entry if exists
                                    $timeline = $order->timelines->firstWhere('status', $status);
                                    
                                    $iconColors = $isCompleted ? 'bg-primary text-white ring-4 ring-white' : 'bg-surface-container text-outline ring-4 ring-white border border-outline-variant';
                                    $icons = [
                                        'pending' => 'pending_actions',
                                        'confirmed' => 'thumb_up',
                                        'packed' => 'inventory_2',
                                        'shipped' => 'local_shipping',
                                        'delivered' => 'check_circle'
                                    ];
                                @endphp
                                <div class="relative">
                                    <div class="absolute -left-[35px] top-0 w-8 h-8 rounded-full flex items-center justify-center {{ $iconColors }} transition-colors z-10">
                                        <span class="material-symbols-outlined text-[16px]">{{ $icons[$status] }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold {{ $isCompleted ? 'text-on-surface' : 'text-secondary' }} capitalize">{{ $status }}</p>
                                        @if($timeline)
                                            <p class="text-xs text-secondary mt-1">{{ $timeline->created_at->format('D, M d Y - h:i A') }}</p>
                                        @elseif($isCurrent)
                                            <p class="text-xs text-primary font-medium mt-1">In Progress...</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Price Details --}}
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-6">
                    <h2 class="text-lg font-bold text-on-surface mb-4">Price Details</h2>
                    
                    <div class="space-y-3 text-sm border-b border-outline-variant pb-4 mb-4">
                        <div class="flex justify-between text-secondary">
                            <span>List Price ({{ $order->items->count() }} items)</span>
                            <span>₹{{ number_format($order->subtotal ?? $order->grand_total, 2) }}</span>
                        </div>
                        @if(isset($order->discount_amount) && $order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                                <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-secondary">
                            <span>Delivery Charges</span>
                            <span class="{{ ($order->shipping_charge == 0) ? 'text-emerald-600 font-medium' : '' }}">
                                {{ ($order->shipping_charge == 0) ? 'FREE' : '₹' . number_format($order->shipping_charge, 2) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-on-surface text-lg">Total Amount</span>
                        <span class="font-bold text-primary text-xl">₹{{ number_format($order->grand_total, 2) }}</span>
                    </div>

                    <div class="p-4 bg-surface-container rounded-lg">
                        <p class="text-sm font-medium text-on-surface mb-2 border-b border-outline-variant pb-2">Payment Information</p>
                        <div class="space-y-1 text-sm text-secondary">
                            <p>Method: <strong class="text-on-surface">{{ strtoupper($order->payment_method) }}</strong></p>
                            <p>Status: 
                                <strong class="{{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Return / Replacement --}}
                @if($activeReturn || $order->order_status === 'delivered')
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-6">
                    <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">assignment_return</span>
                        Returns &amp; Replacements
                    </h2>

                    @if($activeReturn)
                        @php
                            $rStatusColor = match($activeReturn->status) {
                                'pending' => 'bg-amber-100 text-amber-800',
                                'approved' => 'bg-indigo-100 text-indigo-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'completed' => 'bg-emerald-100 text-emerald-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-secondary">Request Type</span>
                                <span class="text-sm font-semibold text-on-surface capitalize">{{ $activeReturn->type }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-secondary">Status</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase {{ $rStatusColor }}">{{ $activeReturn->status }}</span>
                            </div>
                            <div>
                                <p class="text-sm text-secondary">Reason</p>
                                <p class="text-sm text-on-surface mt-1">{{ $activeReturn->reason }}</p>
                            </div>
                            @if($activeReturn->admin_note && in_array($activeReturn->status, ['approved','rejected','completed']))
                                <div class="bg-surface-container rounded-lg p-3">
                                    <p class="text-xs font-semibold text-on-surface mb-1">Response from our team</p>
                                    <p class="text-sm text-secondary">{{ $activeReturn->admin_note }}</p>
                                </div>
                            @endif
                            <p class="text-xs text-secondary">Requested on {{ $activeReturn->created_at->format('M d, Y') }}</p>
                        </div>
                    @elseif($canRequestReturn)
                        <div x-data="{ open: false }">
                            <p class="text-sm text-secondary mb-4">Not happy with your order? You can request a return or replacement within {{ $windowDays }} days of delivery.</p>
                            <button type="button" x-show="!open" @click="open = true"
                                    class="w-full border-2 border-primary text-primary font-semibold py-2.5 rounded-lg hover:bg-primary hover:text-white transition-colors">
                                Request Return / Replacement
                            </button>

                            <form x-show="open" x-cloak method="POST" action="{{ route('orders.return.store', $order->id) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-on-surface mb-2">What would you like?</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center gap-2 border border-outline-variant rounded-lg px-3 py-2 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                            <input type="radio" name="type" value="refund" checked class="text-primary focus:ring-primary">
                                            <span class="text-sm text-on-surface">Refund</span>
                                        </label>
                                        <label class="flex items-center gap-2 border border-outline-variant rounded-lg px-3 py-2 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                            <input type="radio" name="type" value="replacement" class="text-primary focus:ring-primary">
                                            <span class="text-sm text-on-surface">Replacement</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="reason" class="block text-sm font-medium text-on-surface mb-1">Reason <span class="text-error">*</span></label>
                                    <select name="reason" id="reason" required class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary text-sm">
                                        <option value="Damaged or defective item">Damaged or defective item</option>
                                        <option value="Wrong item delivered">Wrong item delivered</option>
                                        <option value="Item not as described">Item not as described</option>
                                        <option value="Quality not satisfactory">Quality not satisfactory</option>
                                        <option value="Missing parts or accessories">Missing parts or accessories</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="customer_note" class="block text-sm font-medium text-on-surface mb-1">Additional details</label>
                                    <textarea name="customer_note" id="customer_note" rows="3" maxlength="1000" placeholder="Tell us more (optional)"
                                              class="w-full border-outline-variant rounded-lg focus:ring-primary focus:border-primary text-sm"></textarea>
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-primary text-white font-semibold py-2.5 rounded-lg hover:bg-on-primary-fixed-variant transition">Submit Request</button>
                                    <button type="button" @click="open = false" class="px-4 py-2.5 border border-outline-variant rounded-lg text-on-surface hover:bg-surface-container transition">Cancel</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <p class="text-sm text-secondary">The {{ $windowDays }}-day return window for this order has passed. Please contact support if you need assistance.</p>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>

    <style>[x-cloak]{display:none !important;}</style>
</x-customer-layout>
