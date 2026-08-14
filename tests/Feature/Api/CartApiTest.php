<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name' => 'Immunity', 'slug' => 'immunity-'.uniqid(), 'status' => 1,
        ]);

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

    public function test_cart_requires_authentication(): void
    {
        $this->getJson('/api/v1/cart')->assertStatus(401);
    }

    public function test_a_user_can_add_a_product_to_their_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/cart', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertCreated()->assertJsonPath('cart_count', 2);
        $this->assertDatabaseHas('cart_items', ['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_adding_more_than_available_stock_is_rejected(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(['stock_quantity' => 1]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/cart', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
    }

    public function test_quantity_is_capped_at_ten_per_line(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(['stock_quantity' => 20]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/cart', [
            'product_id' => $product->id,
            'quantity' => 11,
        ]);

        $response->assertStatus(422);
    }

    public function test_a_user_cannot_update_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($owner, 'sanctum')->postJson('/api/v1/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItem = \App\Models\CartItem::where('user_id', $owner->id)->firstOrFail();

        $response = $this->actingAs($intruder, 'sanctum')
            ->putJson("/api/v1/cart/{$cartItem->id}", ['quantity' => 2]);

        $response->assertStatus(403);
    }
}
