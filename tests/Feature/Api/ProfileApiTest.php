<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_update_their_profile(): void
    {
        $user = User::factory()->create(['email' => 'old@example.com', 'phone' => '9000000000']);

        $response = $this->actingAs($user, 'sanctum')->patchJson('/api/v1/profile', [
            'name' => 'New Name',
            'phone' => '9111111111',
            'email' => 'old@example.com',
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'New Name');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'phone' => '9111111111']);
    }

    public function test_changing_the_email_clears_verification(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user, 'sanctum')->patchJson('/api/v1/profile', [
            'name' => $user->name,
            'phone' => $user->phone ?? '9000000000',
            'email' => 'changed@example.com',
        ])->assertOk();

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_profile_update_requires_name_phone_and_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')->patchJson('/api/v1/profile', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'phone', 'email']);
    }

    public function test_a_user_can_change_their_password_with_the_current_one(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $this->actingAs($user, 'sanctum')->putJson('/api/v1/profile/password', [
            'current_password' => 'secret123',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ])->assertOk();

        $this->assertTrue(Hash::check('newsecret123', $user->fresh()->password));
    }

    public function test_password_change_rejects_a_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $this->actingAs($user, 'sanctum')->putJson('/api/v1/profile/password', [
            'current_password' => 'wrong',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ])->assertStatus(422)->assertJsonValidationErrors('current_password');
    }

    public function test_a_user_can_delete_their_account_with_their_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);
        $user->createToken('web');

        $this->actingAs($user, 'sanctum')->deleteJson('/api/v1/profile', [
            'password' => 'secret123',
        ])->assertOk();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_account_deletion_rejects_a_wrong_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $this->actingAs($user, 'sanctum')->deleteJson('/api/v1/profile', [
            'password' => 'wrong',
        ])->assertStatus(422)->assertJsonValidationErrors('password');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
