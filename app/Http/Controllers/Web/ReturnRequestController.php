<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnRequestController extends Controller
{
    /** Number of days after delivery during which a return may be requested. */
    public const RETURN_WINDOW_DAYS = 7;

    public function store(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Returns are only allowed for delivered orders
        if ($order->order_status !== 'delivered') {
            return back()->with('error', 'Returns can only be requested for delivered orders.');
        }

        // Block if a request is already pending, approved or completed
        if ($order->returnRequests()->whereIn('status', ['pending', 'approved', 'completed'])->exists()) {
            return back()->with('error', 'A return request for this order is already in progress or completed.');
        }

        // Enforce the return window based on the delivery date
        $deliveredAt = optional(
            $order->timelines()->where('status', 'delivered')->latest()->first()
        )->created_at ?? $order->updated_at;

        if ($deliveredAt->lt(now()->subDays(self::RETURN_WINDOW_DAYS))) {
            return back()->with('error', 'The ' . self::RETURN_WINDOW_DAYS . '-day return window for this order has expired.');
        }

        $validated = $request->validate([
            'type'          => 'required|in:refund,replacement',
            'reason'        => 'required|string|max:255',
            'customer_note' => 'nullable|string|max:1000',
        ]);

        $order->returnRequests()->create([
            'user_id'       => Auth::id(),
            'type'          => $validated['type'],
            'reason'        => $validated['reason'],
            'customer_note' => $validated['customer_note'] ?? null,
            'status'        => 'pending',
        ]);

        return back()->with('success', 'Your return request has been submitted. Our team will review it shortly.');
    }
}
