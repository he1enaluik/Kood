<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_supports_search_and_pagination_meta(): void
    {
        $response = $this->getJson('/api/products?q=mesi&category=mesi&per_page=2');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                'links',
            ]);

        $this->assertLessThanOrEqual(2, count($response->json('data')));
    }

    public function test_admin_catalog_requires_jwt(): void
    {
        $this->getJson('/api/admin/products')->assertUnauthorized();
    }

    public function test_admin_can_update_catalog_with_jwt(): void
    {
        $admin = User::factory()->admin()->create();
        $login = $this->postJson('/api/auth/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $token = $login->json('access_token');

        $response = $this->withToken($token)->putJson('/api/admin/products', [
            'overrides' => [
                'test-toode' => [
                    'slug' => 'test-toode',
                    'name' => 'Test Toode',
                    'price' => 9.99,
                    'category' => 'mesi',
                    'origin_filter' => 'poltsamaa',
                    'origin' => 'Põltsamaa',
                    'short_desc' => 'Test kirjeldus tootele.',
                    'image' => 'pildid/test.png',
                    'badge' => null,
                ],
            ],
            'deletedSlugs' => [],
        ]);

        $response->assertOk();
        $this->assertSame('test-toode', $response->json('overrides.test-toode.slug'));
    }
}
