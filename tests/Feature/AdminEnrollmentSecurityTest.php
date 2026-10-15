
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEnrollmentSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_cannot_assign_admin_role(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'regular@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'role' => User::ROLE_ADMIN,
        ]);

        $response->assertCreated();

        $user = User::query()
            ->where('email', 'regular@example.com')
            ->firstOrFail();

        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isTenant());
    }
}
