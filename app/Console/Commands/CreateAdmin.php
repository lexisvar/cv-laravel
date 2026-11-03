<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Creates the admin user from ADMIN_EMAIL / ADMIN_PASSWORD (and optional
 * ADMIN_NAME) if no user with that email exists. Safe to run on every deploy:
 * it never changes an existing user's password.
 */
class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create the admin user from ADMIN_EMAIL and ADMIN_PASSWORD if it does not exist';

    public function handle(): int
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->line('app:create-admin: ADMIN_EMAIL / ADMIN_PASSWORD not set, skipping.');

            return self::SUCCESS;
        }

        if (User::where('email', $email)->exists()) {
            $this->line("app:create-admin: {$email} already exists.");

            return self::SUCCESS;
        }

        if (strlen($password) < 12) {
            $this->error('app:create-admin: ADMIN_PASSWORD must be at least 12 characters.');

            return self::FAILURE;
        }

        User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => $email,
            'password' => Hash::make($password),
        ])->forceFill(['email_verified_at' => now()])->save();

        $this->info("app:create-admin: created {$email}.");

        return self::SUCCESS;
    }
}
