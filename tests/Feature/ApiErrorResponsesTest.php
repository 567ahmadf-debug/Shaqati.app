<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiErrorResponsesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_api(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }

    public function test_unknown_api_route_returns_json_404(): void
    {
        $response = $this->getJson(
            '/api/this-route-does-not-exist'
        );

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    public function test_authenticated_user_can_access_user_endpoint(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/user');

        $response->assertOk();
        $response->assertJsonPath('data.id', $user->id);
    }

    public function test_invalid_profile_data_returns_422(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/profile', [
                'bio' => str_repeat('a', 501),
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['bio']);
    }
}
