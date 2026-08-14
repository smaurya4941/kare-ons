<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService)
    {
    }

    /**
     * Public — optionally authenticated. When a valid Bearer token is
     * present the per-user one-time-usage check is applied, mirroring
     * Web\CouponController@apply.
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $result = $this->couponService->validate(
            (string) $request->input('code'),
            (float) $request->input('subtotal'),
            $request->user('sanctum')
        );

        if ($result['error']) {
            return response()->json(['message' => $result['error']], 422);
        }

        return response()->json([
            'data' => [
                'code' => $result['coupon']->code,
                'type' => $result['coupon']->type,
                'discount' => $result['discount'],
            ],
        ]);
    }
}
