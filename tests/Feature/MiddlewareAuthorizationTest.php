<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MiddlewareAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_route(): void
    {
        $this->getJson('/api/user')
            ->assertUnauthorized();
    }

    public function test_active_user_can_access_protected_route(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/user')
            ->assertOk();
    }

    public function test_non_admin_cannot_access_admin_route(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_TENANT,
        ]);

        $target = User::factory()->create();

        Sanctum::actingAs($user);

        $this->patchJson(
            "/api/admin/users/{$target->id}/suspend"
        )->assertForbidden();
    }

    public function test_suspended_user_cannot_access_protected_route(): void
    {
        $user = User::factory()->suspended()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/user')
            ->assertForbidden()
            ->assertJson([
                'message' => 'Your account is suspended.',
            ]);
    }
}