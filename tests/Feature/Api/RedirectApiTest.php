<?php

namespace Tests\Feature\Api;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_known_path_returns_its_redirect_target(): void
    {
        Redirect::create([
            'from_path' => 'product/old-slug',
            'to_path' => '/product/new-slug',
            'status_code' => 301,
        ]);

        $response = $this->getJson('/api/v1/redirects/lookup?path=product/old-slug');

        $response->assertOk()->assertJson([
            'data' => [
                'to_path' => '/product/new-slug',
                'status_code' => 301,
            ],
        ]);
    }

    public function test_leading_and_trailing_slashes_are_ignored(): void
    {
        Redirect::create([
            'from_path' => 'category/old',
            'to_path' => '/category/new',
            'status_code' => 302,
        ]);

        $response = $this->getJson('/api/v1/redirects/lookup?path=/category/old/');

        $response->assertOk()->assertJsonPath('data.to_path', '/category/new');
    }

    public function test_an_unknown_path_returns_404(): void
    {
        $response = $this->getJson('/api/v1/redirects/lookup?path=nothing-here');

        $response->assertNotFound();
    }

    public function test_a_missing_path_query_param_returns_422(): void
    {
        $response = $this->getJson('/api/v1/redirects/lookup');

        $response->assertStatus(422);
    }
}
