
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoteUserToAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_be_promoted_after_confirmation(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_TENANT,
            'email_verified_at' => now(),
        ]);

        $this->artisan('app:promote-user-to-admin', [
            'email' => $user->email,
        ])
            ->expectsConfirmation(
                "Promote {$user->email} to admin?",
                'yes'
            )
            ->expectsOutput('User promoted to admin successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            User::ROLE_ADMIN,
            $user->fresh()->role
        );
    }

    public function test_unverified_user_cannot_be_promoted(): void
    {
        $user = User::factory()->unverified()->create([
            'role' => User::ROLE_TENANT,
        ]);

        $this->artisan('app:promote-user-to-admin', [
            'email' => $user->email,
        ])
            ->expectsOutput(
                'The user must verify their email before promotion.'
            )
            ->assertExitCode(1);

        $this->assertSame(
            User::ROLE_TENANT,
            $user->fresh()->role
        );
    }

    public function test_nonexistent_user_cannot_be_promoted(): void
    {
        $this->artisan('app:promote-user-to-admin', [
            'email' => 'missing@example.com',
        ])
            ->expectsOutput('User not found.')
            ->assertExitCode(1);
    }

    public function test_promotion_can_be_cancelled(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_TENANT,
            'email_verified_at' => now(),
        ]);

        $this->artisan('app:promote-user-to-admin', [
            'email' => $user->email,
        ])
            ->expectsConfirmation(
                "Promote {$user->email} to admin?",
                'no'
            )
            ->expectsOutput('Operation cancelled.')
            ->assertExitCode(0);

        $this->assertSame(
            User::ROLE_TENANT,
            $user->fresh()->role
        );
    }
}
