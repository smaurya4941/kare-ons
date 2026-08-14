<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\ReturnRequestController as WebReturnRequestController;
use App\Http\Resources\ReturnRequestResource;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Mirrors Web\ReturnRequestController.
 */
class ReturnRequestController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($order->order_status !== 'delivered') {
            return response()->json(['message' => 'Returns can only be requested for delivered orders.'], 422);
        }

        if ($order->returnRequests()->whereIn('status', ['pending', 'approved', 'completed'])->exists()) {
            return response()->json(['message' => 'A return request for this order is already in progress or completed.'], 422);
        }

        $deliveredAt = optional(
            $order->timelines()->where('status', 'delivered')->latest()->first()
        )->created_at ?? $order->updated_at;

        if ($deliveredAt->lt(now()->subDays(WebReturnRequestController::RETURN_WINDOW_DAYS))) {
            return response()->json([
                'message' => 'The '.WebReturnRequestController::RETURN_WINDOW_DAYS.'-day return window for this order has expired.',
            ], 422);
        }

        $validated = $request->validate([
            'type' => 'required|in:refund,replacement',
            'reason' => 'required|string|max:255',
            'customer_note' => 'nullable|string|max:1000',
        ]);

        $returnRequest = $order->returnRequests()->create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'customer_note' => $validated['customer_note'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Your return request has been submitted. Our team will review it shortly.',
            'data' => new ReturnRequestResource($returnRequest),
        ], 201);
    }
}
