<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles AJAX coupon validation from the cart/checkout page.
 * Returns JSON so the frontend can show the discount dynamically.
 */
class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code     = strtoupper(trim($request->code));
        $subtotal = (float) $request->subtotal;

        $coupon = Coupon::where('code', $code)->where('status', true)->first();

        if (! $coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.'], 422);
        }

        $now = now();
        if ($coupon->starts_at && $coupon->starts_at->isAfter($now)) {
            return response()->json(['success' => false, 'message' => 'This coupon is not active yet.'], 422);
        }
        if ($coupon->expires_at && $coupon->expires_at->isBefore($now)) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired.'], 422);
        }
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'This coupon has reached its usage limit.'], 422);
        }
        if ($subtotal < $coupon->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => "A minimum order of ₹{$coupon->minimum_order_amount} is required.",
            ], 422);
        }

        // Check per-user usage
        if (Auth::check()) {
            $used = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', Auth::id())
                ->exists();
            if ($used) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon.'], 422);
            }
        }

        $discount = $coupon->type === 'percentage'
            ? round($subtotal * ($coupon->value / 100), 2)
            : min($coupon->value, $subtotal);

        return response()->json([
            'success'  => true,
            'message'  => "Coupon applied! You save ₹{$discount}.",
            'discount' => $discount,
            'code'     => $coupon->code,
        ]);
    }

    public function remove(Request $request)
    {
        // Simply return success — actual removal is handled client-side / on checkout submit
        return response()->json(['success' => true, 'message' => 'Coupon removed.']);
    }
}
