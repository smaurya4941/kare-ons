<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the customer's order history.
     */
    public function index(Request $request)
    {
        $orders = Auth::user()
            ->orders()
            ->with(['items.product', 'address'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specific order details for the customer.
     */
    public function show(\App\Models\Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.product', 'address', 'timelines' => function($q) {
            $q->latest();
        }, 'returnRequests' => function($q) {
            $q->latest();
        }]);

        // Return / replacement eligibility
        $windowDays = \App\Http\Controllers\Web\ReturnRequestController::RETURN_WINDOW_DAYS;
        $deliveredAt = optional($order->timelines->firstWhere('status', 'delivered'))->created_at ?? $order->updated_at;
        $activeReturn = $order->returnRequests->first();
        $canRequestReturn = $order->order_status === 'delivered'
            && $deliveredAt->gte(now()->subDays($windowDays))
            && $order->returnRequests->whereIn('status', ['pending', 'approved', 'completed'])->isEmpty();

        return view('orders.show', compact('order', 'activeReturn', 'canRequestReturn', 'windowDays'));
    }
}
