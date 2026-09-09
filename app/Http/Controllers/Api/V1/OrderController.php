<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\ReturnRequestController as WebReturnRequestController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

/**
 * Mirrors Web\OrderController.
 */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with(['items.product', 'address'])
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load([
            'items.product',
            'address',
            'timelines' => fn ($q) => $q->latest(),
            'returnRequests' => fn ($q) => $q->latest(),
        ]);

        $windowDays = WebReturnRequestController::RETURN_WINDOW_DAYS;
        $deliveredAt = optional($order->timelines->firstWhere('status', 'delivered'))->created_at ?? $order->updated_at;
        $canRequestReturn = $order->order_status === 'delivered'
            && $deliveredAt->gte(now()->subDays($windowDays))
            && $order->returnRequests->whereIn('status', ['pending', 'approved', 'completed'])->isEmpty();

        $order->can_request_return = $canRequestReturn;
        $order->return_window_days = $windowDays;

        return new OrderResource($order);
    }

    /**
     * Re-open the Razorpay payment for an order the customer placed but never
     * finished paying (closed the Razorpay modal). Returns the same
     * key/order_id/amount shape as POST /checkout so the frontend can hand it
     * straight to Razorpay Checkout.js, then confirm via
     * POST /checkout/verify-payment.
     */
    public function resumePayment(Request $request, Order $order, CheckoutService $checkoutService)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $razorpay = $checkoutService->resumeRazorpayPayment($order);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['data' => $razorpay]);
    }
}
