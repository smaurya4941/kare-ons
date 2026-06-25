<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
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

        $subtotal  = $this->calculateSubtotal($cartItems);
        $shipping  = $subtotal > 500 ? 0 : 50;
        $total     = $subtotal + $shipping;
        $addresses = Auth::check() ? Auth::user()->addresses : collect();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    /**
     * Process checkout form and place the order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'postal_code'    => ['required', 'digits:6'],
            'payment_method' => 'required|in:razorpay,cod',
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

        $subtotal       = $this->calculateSubtotal($cartItems);
        $shipping       = $subtotal > 500 ? 0 : 50;
        $discountAmount = 0;
        $coupon         = null;

        // Apply coupon if provided
        if ($request->filled('coupon_code')) {
            [$coupon, $discountAmount, $couponError] = $this->applyCoupon($request->coupon_code, $subtotal);
            if ($couponError) {
                return back()->withInput()->with('error', $couponError);
            }
        }

        $grandTotal = max(0, $subtotal + $shipping - $discountAmount);

        try {
            $order = DB::transaction(function () use ($request, $cartItems, $subtotal, $shipping, $discountAmount, $grandTotal, $coupon) {

                // Save or get address
                $address = null;
                if (Auth::check()) {
                    $address = Address::create([
                        'user_id'        => Auth::id(),
                        'full_name'      => $request->full_name,
                        'phone'          => $request->phone,
                        'address_line_1' => $request->address_line_1,
                        'address_line_2' => $request->address_line_2,
                        'city'           => $request->city,
                        'state'          => $request->state,
                        'country'        => 'India',
                        'postal_code'    => $request->postal_code,
                    ]);
                }

                // Create Order
                $order = Order::create([
                    'order_number'    => $this->generateOrderNumber(),
                    'user_id'         => Auth::id(),
                    'address_id'      => $address?->id,
                    'subtotal'        => $subtotal,
                    'shipping_charge' => $shipping,
                    'discount_amount' => $discountAmount,
                    'tax_amount'      => 0,
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
                if (Auth::check()) {
                    CartItem::where('user_id', Auth::id())->delete();
                } else {
                    CartItem::where('session_id', Session::getId())->delete();
                }

                return $order;
            });

        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'An unexpected error occurred while placing your order. Please try again.');
        }

        if ($request->payment_method === 'razorpay') {
            // TODO: Initialise Razorpay order and pass to payment page
            return redirect()->route('checkout.payment')
                ->with('order_id', $order->id)
                ->with('message', 'Redirecting to secure payment gateway...');
        }

        // COD — redirect to success page with order number
        return redirect()->route('checkout.success')
            ->with('order_number', $order->order_number)
            ->with('success', "Your order #{$order->order_number} has been placed successfully!");
    }

    public function payment()
    {
        return view('checkout.payment');
    }

    public function success()
    {
        return view('checkout.success');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::with('product')->where('user_id', Auth::id())->get();
        }
        return CartItem::with('product')->where('session_id', Session::getId())->get();
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
