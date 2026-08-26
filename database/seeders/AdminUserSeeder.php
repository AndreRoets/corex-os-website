<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * The admin account for the webinar console.
 *
 * Run it on its own:
 *
 *     php artisan db:seed --class=AdminUserSeeder
 *
 * Idempotent — running it again resets the password for that address rather
 * than failing on the unique index, which is what you want when someone is
 * locked out and you just need the known credentials back.
 *
 * NOTE: these credentials are in the repository, so treat them as public.
 * Anyone with read access to this code can sign in and read every registrant's
 * name, company, email and phone number. Change the password after first sign-in
 * with `php artisan corex:admin andre@corexos.co.za`, which prompts for it
 * without writing it down anywhere.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // `password` is cast to `hashed` on the User model, so this is stored as
        // a bcrypt hash — never as the plain string, even briefly.
        $user = User::updateOrCreate(
            ['email' => 'andre@corexos.co.za'],
            [
                'name' => 'Andre Roets',
                'password' => 'Mineme098@',
                'email_verified_at' => now(),
            ],
        );

        $this->command?->info("Admin account ready: {$user->email}");
    }
}
