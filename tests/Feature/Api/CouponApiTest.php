<?php

namespace Tests\Feature\Api;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_coupon_applies_a_discount(): void
    {
        Coupon::create([
            'code' => 'KARE10',
            'type' => 'percentage',
            'value' => 10,
            'minimum_order_amount' => 0,
            'status' => 1,
        ]);

        $response = $this->postJson('/api/v1/coupons/validate', [
            'code' => 'kare10',
            'subtotal' => 1000,
        ]);

        $response->assertOk()->assertJsonPath('data.discount', 100);
    }

    public function test_an_unknown_coupon_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/coupons/validate', [
            'code' => 'DOESNOTEXIST',
            'subtotal' => 1000,
        ]);

        $response->assertStatus(422);
    }

    public function test_a_coupon_below_minimum_order_is_rejected(): void
    {
        Coupon::create([
            'code' => 'BIG500',
            'type' => 'fixed',
            'value' => 50,
            'minimum_order_amount' => 2000,
            'status' => 1,
        ]);

        $response = $this->postJson('/api/v1/coupons/validate', [
            'code' => 'BIG500',
            'subtotal' => 500,
        ]);

        $response->assertStatus(422);
    }

    public function test_a_fixed_discount_never_exceeds_the_subtotal(): void
    {
        Coupon::create([
            'code' => 'FLAT500',
            'type' => 'fixed',
            'value' => 500,
            'minimum_order_amount' => 0,
            'status' => 1,
        ]);

        $response = $this->postJson('/api/v1/coupons/validate', [
            'code' => 'FLAT500',
            'subtotal' => 200,
        ]);

        $response->assertOk()->assertJsonPath('data.discount', 200);
    }
}
