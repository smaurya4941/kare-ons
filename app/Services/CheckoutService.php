<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\AdminNotification;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\CouponUsage;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Shared checkout logic used by both the Blade checkout flow
 * (Web\CheckoutController) and the API checkout endpoints. Extracted so the
 * cart→order transaction (stock locking, inventory ledger, coupon usage,
 * Razorpay order creation) has exactly one implementation.
 */
class CheckoutService
{
    public function __construct(protected CouponService $couponService)
    {
    }

    /**
     * Cart items for the given user, eager-loaded with product.
     */
    public function getCartItems(User $user)
    {
        return CartItem::with('product')->where('user_id', $user->id)->get();
    }

    public function calculateSubtotal($cartItems): float
    {
        return $cartItems->sum(fn ($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity);
    }

    public function calculateTax($cartItems): float
    {
        $taxAmount = 0;
        foreach ($cartItems as $item) {
            $unitPrice = $item->product->sale_price ?? $item->product->price;
            $taxRate = $item->product->tax ? $item->product->tax->rate : 0;
            $taxAmount += ($unitPrice * $item->quantity) * ($taxRate / 100);
        }

        return $taxAmount;
    }

    public function calculateShipping(float $subtotal): float
    {
        $shippingCharge = (float) setting('shipping_charge', 0);
        $freeShippingThreshold = (float) setting('free_shipping_amount', 0);

        return ($freeShippingThreshold > 0 && $subtotal >= $freeShippingThreshold) ? 0 : $shippingCharge;
    }

    /**
     * Validate every cart item is still purchasable (active + in stock).
     *
     * @return string|null An error message for the first failing item, or null if all pass.
     */
    public function validateStock($cartItems): ?string
    {
        foreach ($cartItems as $item) {
            if (! $item->product || ! $item->product->status) {
                return "'{$item->product?->name}' is no longer available.";
            }
            if ($item->product->stock_quantity < $item->quantity) {
                return "Not enough stock for '{$item->product->name}'. Please update your cart.";
            }
        }

        return null;
    }

    /**
     * Build the checkout summary: cart items, subtotal, tax, shipping, total.
     *
     * @return array{cartItems: mixed, subtotal: float, taxAmount: float, shipping: float, total: float}
     */
    public function buildSummary(User $user): array
    {
        $cartItems = $this->getCartItems($user);

        $subtotal = $this->calculateSubtotal($cartItems);
        $taxAmount = $this->calculateTax($cartItems);
        $shipping = $this->calculateShipping($subtotal);
        $total = $subtotal + $shipping + $taxAmount;

        return compact('cartItems', 'subtotal', 'taxAmount', 'shipping', 'total');
    }

    /**
     * Place an order for the given user: creates the address + order + items
     * inside a DB transaction (row-locking stock), records the coupon usage,
     * clears the cart, then branches Razorpay-order-creation vs COD email.
     *
     * @param  array  $data  full_name, phone, address_line_1, address_line_2,
     *                       city, state, postal_code, payment_method, coupon_code
     * @return array{order: Order, razorpay: ?array} razorpay is
     *         ['key' => ..., 'order_id' => ..., 'amount' => ...] when the
     *         payment method is razorpay, otherwise null (COD — already emailed).
     *
     * @throws InsufficientStockException
     * @throws \RuntimeException with the coupon error message if the coupon is invalid
     * @throws \Exception if cart is empty or the Razorpay API call fails
     */
    public function placeOrder(User $user, array $data): array
    {
        $cartItems = $this->getCartItems($user);
        if ($cartItems->isEmpty()) {
            throw new \RuntimeException('Your cart is empty.');
        }

        if ($stockError = $this->validateStock($cartItems)) {
            throw new InsufficientStockException($stockError);
        }

        $subtotal = $this->calculateSubtotal($cartItems);
        $taxAmount = $this->calculateTax($cartItems);
        $shipping = $this->calculateShipping($subtotal);

        $discountAmount = 0;
        $coupon = null;

        if (! empty($data['coupon_code'])) {
            $result = $this->couponService->validate($data['coupon_code'], $subtotal, $user);
            if ($result['error']) {
                throw new \RuntimeException($result['error']);
            }
            $coupon = $result['coupon'];
            $discountAmount = $result['discount'];
        }

        $grandTotal = max(0, $subtotal + $shipping + $taxAmount - $discountAmount);

        $order = DB::transaction(function () use ($user, $data, $cartItems, $subtotal, $shipping, $taxAmount, $discountAmount, $grandTotal, $coupon) {
            $address = Address::create([
                'user_id' => $user->id,
                'full_name' => $data['full_name'],
                'phone' => $data['phone'],
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'] ?? null,
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => 'India',
                'postal_code' => $data['postal_code'],
            ]);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'address_id' => $address?->id,
                'subtotal' => $subtotal,
                'shipping_charge' => $shipping,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => null,
            ]);

            foreach ($cartItems as $item) {
                $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

                if (! $product || $product->stock_quantity < $item->quantity) {
                    throw new InsufficientStockException(
                        "Insufficient stock for '{$item->product->name}'. Please update your cart."
                    );
                }

                $unitPrice = $item->product->sale_price ?? $item->product->price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'total' => $unitPrice * $item->quantity,
                ]);

                $product->decrement('stock_quantity', $item->quantity);

                InventoryTransaction::create([
                    'product_id' => $item->product_id,
                    'user_id' => $user->id,
                    'type' => 'order_fulfillment',
                    'quantity' => -$item->quantity,
                    'reference_id' => $order->order_number,
                    'notes' => 'Stock deducted for order placement.',
                ]);
            }

            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                ]);
                $coupon->increment('used_count');
            }

            CartItem::where('user_id', $user->id)->delete();

            return $order;
        });

        $this->notifyAfterOrder($order);

        if ($data['payment_method'] === 'razorpay') {
            $razorpay = $this->createRazorpayOrder($order);

            return ['order' => $order, 'razorpay' => $razorpay];
        }

        if ($order->user) {
            Mail::to($order->user->email)->send(new \App\Mail\OrderPlaced($order));
        }

        return ['order' => $order, 'razorpay' => null];
    }

    /**
     * Best-effort admin/low-stock notifications. Never allowed to disrupt checkout.
     */
    protected function notifyAfterOrder(Order $order): void
    {
        try {
            AdminNotification::notifyNewOrder($order);

            $threshold = (int) setting('low_stock_threshold', 10);
            $order->loadMissing('items');
            $productIds = $order->items->pluck('product_id')->filter()->unique();
            foreach (Product::whereIn('id', $productIds)->get() as $product) {
                if ((int) $product->stock_quantity <= $threshold) {
                    AdminNotification::notifyLowStock($product);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * @return array{key: string, order_id: string, amount: int, currency: string}
     */
    protected function createRazorpayOrder(Order $order): array
    {
        $api = new \Razorpay\Api\Api(setting('razorpay_key'), setting('razorpay_secret'));

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => round($order->grand_total * 100), // paise
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $order->grand_total,
            'currency' => 'INR',
            'status' => 'pending',
        ]);

        return [
            'key' => setting('razorpay_key'),
            'order_id' => $razorpayOrder['id'],
            'amount' => (int) round($order->grand_total * 100),
            'currency' => 'INR',
        ];
    }

    /**
     * Verify a Razorpay browser-redirect callback and confirm the order.
     * Mirrors CheckoutController@callback; idempotent via RazorpayPaymentService.
     *
     * @throws \Exception on signature verification failure
     */
    public function verifyPayment(array $input, RazorpayPaymentService $paymentService): Order
    {
        $api = new \Razorpay\Api\Api(setting('razorpay_key'), setting('razorpay_secret'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $input['razorpay_order_id'],
                'razorpay_payment_id' => $input['razorpay_payment_id'],
                'razorpay_signature' => $input['razorpay_signature'],
            ]);
        } catch (\Exception $e) {
            if (isset($input['razorpay_order_id'])) {
                $payment = Payment::where('razorpay_order_id', $input['razorpay_order_id'])->first();
                if ($payment) {
                    $paymentService->markFailed($payment);
                }
            }
            throw $e;
        }

        $payment = Payment::where('razorpay_order_id', $input['razorpay_order_id'])->firstOrFail();
        $paymentService->markPaid($payment, $input['razorpay_payment_id']);

        return $payment->order;
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'KO-'.strtoupper(Str::random(8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
