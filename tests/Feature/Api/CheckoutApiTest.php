<?php

namespace Tests\Feature\Api;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeProduct(array $overrides = []): Product
    {
        $category = Category::create(['name' => 'Immunity', 'slug' => 'immunity-'.uniqid(), 'status' => 1]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Ashwagandha Capsules',
            'slug' => 'ashwagandha-'.uniqid(),
            'sku' => 'SKU-'.uniqid(),
            'description' => 'Test product',
            'price' => 500,
            'stock_quantity' => 5,
            'main_image' => 'products/test.jpg',
            'status' => 1,
        ], $overrides));
    }

    protected function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Asha Rao',
            'phone' => '9876543210',
            'address_line_1' => '12 MG Road',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'postal_code' => '560001',
            'payment_method' => 'cod',
        ], $overrides);
    }

    public function test_a_cod_order_can_be_placed_and_decrements_stock(): void
    {
        Mail::fake();

        PaymentMethod::create(['name' => 'Cash on Delivery', 'code' => 'cod', 'status' => 1]);
        $user = User::factory()->create();
        $product = $this->makeProduct(['stock_quantity' => 5, 'price' => 500]);

        CartItem::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/checkout', $this->checkoutPayload());

        $response->assertCreated()->assertJsonPath('data.razorpay', null);

        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'order_status' => 'pending', 'payment_method' => 'cod']);
        $this->assertEquals(3, $product->fresh()->stock_quantity);
        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'type' => 'order_fulfillment',
            'quantity' => -2,
        ]);
        $this->assertDatabaseCount('cart_items', 0);
        // OrderPlaced implements ShouldQueue, so PendingMail::send() redirects
        // it onto the queue rather than sending inline — assert accordingly.
        Mail::assertQueued(\App\Mail\OrderPlaced::class);
    }

    public function test_checkout_with_an_empty_cart_is_rejected(): void
    {
        PaymentMethod::create(['name' => 'Cash on Delivery', 'code' => 'cod', 'status' => 1]);
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/checkout', $this->checkoutPayload());

        $response->assertStatus(422)->assertJsonPath('message', 'Your cart is empty.');
    }

    public function test_checkout_fails_cleanly_when_stock_runs_out_before_order_creation(): void
    {
        PaymentMethod::create(['name' => 'Cash on Delivery', 'code' => 'cod', 'status' => 1]);
        $user = User::factory()->create();
        $product = $this->makeProduct(['stock_quantity' => 1]);

        CartItem::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        // Simulate another order winning the race for the last unit.
        $product->update(['stock_quantity' => 0]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/checkout', $this->checkoutPayload());

        $response->assertStatus(409);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('cart_items', 1); // cart untouched — transaction rolled back
    }

    public function test_checkout_requires_a_valid_payment_method(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();
        CartItem::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/checkout', $this->checkoutPayload(['payment_method' => 'bitcoin']));

        $response->assertStatus(422)->assertJsonValidationErrors('payment_method');
    }
}
