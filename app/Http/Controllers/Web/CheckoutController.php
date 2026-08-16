<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\RazorpayPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(protected CheckoutService $checkoutService)
    {
    }

    /**
     * Show checkout page with cart summary and saved addresses.
     */
    public function index()
    {
        $summary = $this->checkoutService->buildSummary(Auth::user());
        $cartItems = $summary['cartItems'];

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty. Please add products before checking out.');
        }

        if ($error = $this->checkoutService->validateStock($cartItems)) {
            return redirect()->route('cart.index')->with('error', $error);
        }

        $subtotal = $summary['subtotal'];
        $shipping = $summary['shipping'];
        $taxAmount = $summary['taxAmount'];
        $total = $summary['total'];
        $addresses = Auth::user()->addresses;
        $paymentMethods = \App\Models\PaymentMethod::where('status', true)->get();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'taxAmount', 'total', 'addresses', 'paymentMethods'));
    }

    /**
     * Process checkout form and place the order.
     */
    public function store(Request $request)
    {
        $validPaymentMethods = \App\Models\PaymentMethod::where('status', true)->pluck('code')->toArray();

        $validated = $request->validate([
            'address_id'     => 'nullable|integer|exists:addresses,id',
            'full_name'      => 'required_without:address_id|string|max:255',
            'phone'          => 'required_without:address_id|string|max:20',
            'address_line_1' => 'required_without:address_id|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required_without:address_id|string|max:100',
            'state'          => 'required_without:address_id|string|max:100',
            'postal_code'    => 'required_without:address_id|string|max:20',
            'payment_method' => 'required|in:' . implode(',', $validPaymentMethods),
            'coupon_code'    => 'nullable|string|max:50',
        ]);

        try {
            $result = $this->checkoutService->placeOrder(Auth::user(), $validated);
        } catch (\App\Exceptions\InsufficientStockException $e) {
            // Stock ran out between the pre-check and the locked re-check; the whole
            // transaction is rolled back, so no order/stock changes persisted.
            return redirect()->route('cart.index')->withInput()->with('error', $e->getMessage());
        } catch (\RuntimeException $e) {
            // Empty cart or invalid coupon — surfaced verbatim by the service.
            return $e->getMessage() === 'Your cart is empty.'
                ? redirect()->route('shop.index')->with('error', $e->getMessage())
                : back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'An unexpected error occurred while placing your order. Please try again.');
        }

        $order = $result['order'];

        if ($result['razorpay']) {
            return redirect()->route('checkout.payment')
                ->with('order_id', $order->id)
                ->with('message', 'Redirecting to secure payment gateway...');
        }

        // COD — redirect to success page with order number
        return redirect()->route('checkout.success')
            ->with('order_number', $order->order_number)
            ->with('payment_method', $order->payment_method)
            ->with('toast', toast_payload(
                "Your order #{$order->order_number} has been placed successfully!",
                'success',
                'Order Placed',
                ['label' => 'View My Orders', 'url' => route('orders.index')]
            ));
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

    public function callback(Request $request, RazorpayPaymentService $paymentService)
    {
        try {
            $order = $this->checkoutService->verifyPayment($request->all(), $paymentService);

            return redirect()->route('checkout.success')
                ->with('order_number', $order->order_number)
                ->with('payment_method', 'razorpay')
                ->with('toast', toast_payload(
                    "Payment successful! Your order #{$order->order_number} has been placed.",
                    'success',
                    'Order Placed',
                    ['label' => 'View My Orders', 'url' => route('orders.index')]
                ));
        } catch (\Exception $e) {
            report($e);
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
}
