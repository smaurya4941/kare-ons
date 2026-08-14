<?php

namespace Tests\Feature\Api;

use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReturnRequestApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeDeliveredOrder(User $user, ?\Illuminate\Support\Carbon $deliveredAt = null): Order
    {
        $order = Order::create([
            'order_number' => 'KO-'.uniqid(),
            'user_id' => $user->id,
            'subtotal' => 500,
            'shipping_charge' => 0,
            'grand_total' => 500,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
        ]);

        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => 'delivered',
            'created_at' => $deliveredAt ?? now(),
        ]);

        return $order;
    }

    public function test_a_return_can_be_requested_for_a_recently_delivered_order(): void
    {
        $user = User::factory()->create();
        $order = $this->makeDeliveredOrder($user);

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/v1/orders/{$order->id}/return", [
            'type' => 'refund',
            'reason' => 'Item arrived damaged',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('return_requests', ['order_id' => $order->id, 'status' => 'pending']);
    }

    public function test_a_return_is_rejected_outside_the_seven_day_window(): void
    {
        $user = User::factory()->create();
        $order = $this->makeDeliveredOrder($user, now()->subDays(10));

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/v1/orders/{$order->id}/return", [
            'type' => 'refund',
            'reason' => 'Item arrived damaged',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('return_requests', 0);
    }

    public function test_a_second_return_request_is_blocked_while_one_is_pending(): void
    {
        $user = User::factory()->create();
        $order = $this->makeDeliveredOrder($user);

        $order->returnRequests()->create([
            'user_id' => $user->id,
            'type' => 'refund',
            'reason' => 'first request',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/v1/orders/{$order->id}/return", [
            'type' => 'refund',
            'reason' => 'second request',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('return_requests', 1);
    }

    public function test_a_user_cannot_request_a_return_for_another_users_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = $this->makeDeliveredOrder($owner);

        $response = $this->actingAs($intruder, 'sanctum')->postJson("/api/v1/orders/{$order->id}/return", [
            'type' => 'refund',
            'reason' => 'not mine',
        ]);

        $response->assertStatus(403);
    }

    public function test_order_show_reports_can_request_return_correctly(): void
    {
        $user = User::factory()->create();
        $order = $this->makeDeliveredOrder($user);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/orders/{$order->id}");

        $response->assertOk()->assertJsonPath('data.can_request_return', true);
    }
}
