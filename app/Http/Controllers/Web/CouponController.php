<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles AJAX coupon validation from the cart/checkout page.
 * Returns JSON so the frontend can show the discount dynamically.
 */
class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService)
    {
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        try {
            $result = $this->couponService->validate($request->code, (float) $request->subtotal, Auth::user());

            if ($result['error']) {
                return response()->json(['success' => false, 'message' => $result['error']], 422);
            }

            $coupon = $result['coupon'];
            $discount = $result['discount'];

            return response()->json([
                'success'  => true,
                'message'  => "Coupon applied! You save ₹{$discount}.",
                'discount' => $discount,
                'code'     => $coupon->code,
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred while applying the coupon.'], 500);
        }
    }

    public function remove(Request $request)
    {
        // Simply return success — actual removal is handled client-side / on checkout submit
        return response()->json(['success' => true, 'message' => 'Coupon removed.']);
    }
}
