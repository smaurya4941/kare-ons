<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show checkout page with cart summary and saved addresses.
     */
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty. Please add products before checking out.');
        }

        // Validate that every product is still in stock
        foreach ($cartItems as $item) {
            if (! $item->product || ! $item->product->status) {
                return redirect()->route('cart.index')->with('error', "'{$item->product?->name}' is no longer available.");
            }
            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Not enough stock for '{$item->product->name}'. Please update your cart.");
            }
        }

        // Calculate total tax
        $taxAmount = 0;
        foreach ($cartItems as $item) {
            $unitPrice = $item->product->sale_price ?? $item->product->price;
            $taxRate = $item->product->tax ? $item->product->tax->rate : 0;
            $taxAmount += ($unitPrice * $item->quantity) * ($taxRate / 100);
        }

        $subtotal  = $this->calculateSubtotal($cartItems);
        // Default shipping for display purposes, will be recalculated on submission
        $defaultZone = \App\Models\ShippingZone::where('is_default', true)->first();
        $shipping  = $defaultZone ? ($subtotal >= $defaultZone->free_shipping_threshold ? 0 : $defaultZone->base_charge) : 0;
        $total     = $subtotal + $shipping + $taxAmount;
        $addresses = Auth::check() ? Auth::user()->addresses : collect();
        $paymentMethods = \App\Models\PaymentMethod::where('status', true)->get();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'taxAmount', 'total', 'addresses', 'paymentMethods'));
    }

    /**
     * Process checkout form and place the order.
     */
    public function store(Request $request)
    {
        $validPaymentMethods = \App\Models\PaymentMethod::where('status', true)->pluck('code')->toArray();
        
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'payment_method' => 'required|in:' . implode(',', $validPaymentMethods),
            'coupon_code'    => 'nullable|string|max:50',
        ]);

        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        // Re-verify stock for all items before placing order
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Insufficient stock for '{$item->product->name}'. Please update your cart.");
            }
        }

        $subtotal = $this->calculateSubtotal($cartItems);
        
        // Calculate tax
        $taxAmount = 0;
        foreach ($cartItems as $item) {
            $unitPrice = $item->product->sale_price ?? $item->product->price;
            $taxRate = $item->product->tax ? $item->product->tax->rate : 0;
            $taxAmount += ($unitPrice * $item->quantity) * ($taxRate / 100);
        }

        // Calculate dynamic shipping
        $postalCode = $request->postal_code;
        $shippingZone = \App\Models\ShippingZone::where('is_active', true)
            ->where(function ($query) use ($postalCode) {
                $query->where('coverage', 'like', "%{$postalCode}%")
                      ->orWhere('is_default', true);
            })->orderBy('is_default', 'asc')->first();

        $shipping = 0;
        if ($shippingZone) {
            if ($shippingZone->free_shipping_threshold && $subtotal >= $shippingZone->free_shipping_threshold) {
                $shipping = 0;
            } else {
                $shipping = $shippingZone->base_charge;
            }
            if ($request->payment_method === 'cod') {
                $shipping += $shippingZone->cod_charge;
            }
        }

        $discountAmount = 0;
        $coupon         = null;

        // Apply coupon if provided
        if ($request->filled('coupon_code')) {
            [$coupon, $discountAmount, $couponError] = $this->applyCoupon($request->coupon_code, $subtotal);
            if ($couponError) {
                return back()->withInput()->with('error', $couponError);
            }
        }

        $grandTotal = max(0, $subtotal + $shipping + $taxAmount - $discountAmount);

        try {
            $order = DB::transaction(function () use ($request, $cartItems, $subtotal, $shipping, $taxAmount, $discountAmount, $grandTotal, $coupon) {

                // Save address for both authenticated and guest users
                $address = Address::create([
                    'user_id'        => Auth::id(), // Will be null for guests
                    'full_name'      => $request->full_name,
                    'phone'          => $request->phone,
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city'           => $request->city,
                    'state'          => $request->state,
                    'country'        => 'India',
                    'postal_code'    => $request->postal_code,
                ]);

                // Create Order
                $order = Order::create([
                    'order_number'    => $this->generateOrderNumber(),
                    'user_id'         => Auth::id(),
                    'address_id'      => $address?->id,
                    'subtotal'        => $subtotal,
                    'shipping_charge' => $shipping,
                    'discount_amount' => $discountAmount,
                    'tax_amount'      => $taxAmount,
                    'grand_total'     => $grandTotal,
                    'payment_method'  => $request->payment_method,
                    'payment_status'  => $request->payment_method === 'cod' ? 'pending' : 'pending',
                    'order_status'    => 'pending',
                    'notes'           => null,
                ]);

                // Create Order Items & decrement stock
                foreach ($cartItems as $item) {
                    $unitPrice = $item->product->sale_price ?? $item->product->price;
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->name,
                        'sku'          => $item->product->sku,
                        'price'        => $unitPrice,
                        'quantity'     => $item->quantity,
                        'total'        => $unitPrice * $item->quantity,
                    ]);

                    // Decrement stock
                    $item->product->decrement('stock_quantity', $item->quantity);

                    // Log inventory transaction
                    InventoryTransaction::create([
                        'product_id' => $item->product_id,
                        'user_id' => Auth::id(),
                        'type' => 'order_fulfillment',
                        'quantity' => -$item->quantity, // Negative for OUT
                        'reference_id' => $order->order_number,
                        'notes' => 'Stock deducted for order placement.',
                    ]);
                }

                // Record coupon usage
                if ($coupon) {
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id'   => Auth::id(),
                        'order_id'  => $order->id,
                    ]);
                    $coupon->increment('used_count');
                }

                // Clear cart
                CartItem::where('user_id', Auth::id())->delete();

                return $order;
            });

        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'An unexpected error occurred while placing your order. Please try again.');
        }

        if ($request->payment_method === 'razorpay') {
            try {
                $api = new \Razorpay\Api\Api(setting('razorpay_key'), setting('razorpay_secret'));
                
                $razorpayOrder = $api->order->create([
                    'receipt'         => $order->order_number,
                    'amount'          => round($order->grand_total * 100), // in paise
                    'currency'        => 'INR',
                    'payment_capture' => 1
                ]);

                \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => $order->grand_total,
                    'currency' => 'INR',
                    'status' => 'pending'
                ]);

                return redirect()->route('checkout.payment')
                    ->with('order_id', $order->id)
                    ->with('message', 'Redirecting to secure payment gateway...');
            } catch (\Exception $e) {
                report($e);
                return redirect()->route('cart.index')->with('error', 'Payment gateway initialization failed. Please try again.');
            }
        }

        // Send Email Notification
        if ($order->user) {
            \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderPlaced($order));
        }

        // COD — redirect to success page with order number
        return redirect()->route('checkout.success')
            ->with('order_number', $order->order_number)
            ->with('payment_method', $order->payment_method)
            ->with('success', "Your order #{$order->order_number} has been placed successfully!");
    }

    public function payment()
    {
        $orderId = session('order_id');
        if (!$orderId) {
            return redirect()->route('shop.index')->with('error', 'Invalid payment session.');
        }

        $order = Order::with(['payment', 'user'])->findOrFail($orderId);
        
        // Re-flash order_id in case they refresh the page
        session()->flash('order_id', $orderId);

        return view('checkout.payment', compact('order'));
    }

    public function callback(Request $request, \App\Services\RazorpayPaymentService $paymentService)
    {
        $input = $request->all();

        try {
            $api = new \Razorpay\Api\Api(setting('razorpay_key'), setting('razorpay_secret'));
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $input['razorpay_order_id'],
                'razorpay_payment_id' => $input['razorpay_payment_id'],
                'razorpay_signature'  => $input['razorpay_signature'],
            ]);

            // Signature verified. Confirm the order via the shared service — this is
            // idempotent, so if the webhook already confirmed it nothing is duplicated.
            $payment = \App\Models\Payment::where('razorpay_order_id', $input['razorpay_order_id'])->firstOrFail();
            $paymentService->markPaid($payment, $input['razorpay_payment_id']);

            $order = $payment->order;

            return redirect()->route('checkout.success')
                ->with('order_number', $order->order_number)
                ->with('payment_method', 'razorpay')
                ->with('success', "Payment successful! Your order #{$order->order_number} has been placed.");

        } catch (\Exception $e) {
            report($e);

            // Mark payment as failed (idempotent; never downgrades a success)
            if (isset($input['razorpay_order_id'])) {
                $payment = \App\Models\Payment::where('razorpay_order_id', $input['razorpay_order_id'])->first();
                if ($payment) {
                    $paymentService->markFailed($payment);
                }
            }

            return redirect()->route('cart.index')->with('error', 'Payment failed or signature verification failed. Please try again.');
        }
    }

    public function success()
    {
        if (!session('order_number')) {
            return redirect()->route('shop.index');
        }
        return view('checkout.success');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function getCartItems()
    {
        return CartItem::with('product')->where('user_id', Auth::id())->get();
    }

    protected function calculateSubtotal($cartItems): float
    {
        return $cartItems->sum(fn($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity);
    }

    /**
     * Validate and compute coupon discount.
     *
     * @return array [?Coupon $coupon, float $discountAmount, ?string $errorMessage]
     */
    protected function applyCoupon(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))
            ->where('status', true)
            ->first();

        if (! $coupon) {
            return [null, 0, 'Invalid or expired coupon code.'];
        }

        $now = now();
        if ($coupon->starts_at && $coupon->starts_at->isAfter($now)) {
            return [null, 0, 'This coupon is not active yet.'];
        }
        if ($coupon->expires_at && $coupon->expires_at->isBefore($now)) {
            return [null, 0, 'This coupon has expired.'];
        }
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return [null, 0, 'This coupon has reached its usage limit.'];
        }
        if ($subtotal < $coupon->minimum_order_amount) {
            return [null, 0, "A minimum order of ₹{$coupon->minimum_order_amount} is required to use this coupon."];
        }

        // Per-user usage check
        if (Auth::check()) {
            $used = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', Auth::id())
                ->exists();
            if ($used) {
                return [null, 0, 'You have already used this coupon.'];
            }
        }

        $discount = $coupon->type === 'percentage'
            ? round($subtotal * ($coupon->value / 100), 2)
            : min($coupon->value, $subtotal);

        return [$coupon, $discount, null];
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'KO-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
