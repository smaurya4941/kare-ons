<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;

/**
 * Shared coupon validation used by both the Blade AJAX endpoint
 * (Web\CouponController) and the checkout flow (CheckoutService), and by the
 * API's coupon-validate endpoint. Keeping this in one place means the rules
 * (active window, usage limit, minimum order, per-user usage, discount
 * formula) can never drift between callers.
 */
class CouponService
{
    /**
     * @return array{coupon: ?Coupon, discount: float, error: ?string}
     */
    public function validate(string $code, float $subtotal, ?User $user): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))
            ->where('status', true)
            ->first();

        if (! $coupon) {
            return ['coupon' => null, 'discount' => 0, 'error' => 'Invalid or expired coupon code.'];
        }

        $now = now();
        if ($coupon->starts_at && $coupon->starts_at->isAfter($now)) {
            return ['coupon' => null, 'discount' => 0, 'error' => 'This coupon is not active yet.'];
        }
        if ($coupon->expires_at && $coupon->expires_at->isBefore($now)) {
            return ['coupon' => null, 'discount' => 0, 'error' => 'This coupon has expired.'];
        }
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return ['coupon' => null, 'discount' => 0, 'error' => 'This coupon has reached its usage limit.'];
        }
        if ($subtotal < $coupon->minimum_order_amount) {
            return [
                'coupon' => null,
                'discount' => 0,
                'error' => "A minimum order of ₹{$coupon->minimum_order_amount} is required to use this coupon.",
            ];
        }

        if ($user) {
            $used = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->exists();
            if ($used) {
                return ['coupon' => null, 'discount' => 0, 'error' => 'You have already used this coupon.'];
            }
        }

        $discount = $coupon->type === 'percentage'
            ? round($subtotal * ($coupon->value / 100), 2)
            : min($coupon->value, $subtotal);

        return ['coupon' => $coupon, 'discount' => $discount, 'error' => null];
    }
}
