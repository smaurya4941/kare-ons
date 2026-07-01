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
        }]);

        return view('orders.show', compact('order'));
    }
}
