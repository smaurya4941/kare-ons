<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\ReturnRequestController as WebReturnRequestController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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
}
