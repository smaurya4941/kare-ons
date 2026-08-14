<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\OrderResource;
use App\Models\PaymentMethod;
use App\Services\CheckoutService;
use App\Services\RazorpayPaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(protected CheckoutService $checkoutService)
    {
    }

    /**
     * Cart summary + saved addresses, mirrors Web\CheckoutController@index.
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        $summary = $this->checkoutService->buildSummary($user);

        return response()->json([
            'data' => [
                'items' => CartItemResource::collection($summary['cartItems']),
                'subtotal' => round($summary['subtotal'], 2),
                'tax_amount' => round($summary['taxAmount'], 2),
                'shipping' => round($summary['shipping'], 2),
                'total' => round($summary['total'], 2),
                'addresses' => AddressResource::collection($user->addresses),
                'payment_methods' => PaymentMethod::where('status', true)->get(['id', 'code', 'name']),
            ],
        ]);
    }

    /**
     * Place the order. Mirrors Web\CheckoutController@store.
     *
     * COD: returns the created order immediately.
     * Razorpay: returns the order plus the Razorpay order id/key/amount the
     * frontend needs to open Razorpay Checkout.js.
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $result = $this->checkoutService->placeOrder($request->user(), $request->validated());
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $order = $result['order'];

        return response()->json([
            'data' => [
                'order' => new OrderResource($order),
                'razorpay' => $result['razorpay'],
            ],
        ], 201);
    }

    /**
     * Verify a Razorpay checkout callback (order_id/payment_id/signature)
     * and confirm the order. Mirrors Web\CheckoutController@callback.
     */
    public function verifyPayment(Request $request, RazorpayPaymentService $paymentService)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        try {
            $order = $this->checkoutService->verifyPayment($request->all(), $paymentService);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment verification failed. Please try again.'], 422);
        }

        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json(['data' => new OrderResource($order)]);
    }
}
