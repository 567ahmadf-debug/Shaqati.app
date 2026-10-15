<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteUserToAdmin extends Command
{
    protected $signature = 'app:promote-user-to-admin {email}';

    protected $description = 'Promote an existing verified user to admin';

    public function handle(): int
    {
        $email = (string) $this->argument('email');

        $user = User::query()
            ->where('email', $email)
            ->first();

        if (! $user) {
            $this->error('User not found.');

            return self::FAILURE;
        }

        if (! $user->hasVerifiedEmail()) {
            $this->error(
                'The user must verify their email before promotion.'
            );

            return self::FAILURE;
        }

        if ($user->isAdmin()) {
            $this->info('This user is already an admin.');

            return self::SUCCESS;
        }

        if (! $this->confirm(
            "Promote {$user->email} to admin?"
        )) {
            $this->warn('Operation cancelled.');

            return self::SUCCESS;
        }

        $user->role = User::ROLE_ADMIN;
        $user->save();

        $this->info('User promoted to admin successfully.');

        return self::SUCCESS;
    }
}
