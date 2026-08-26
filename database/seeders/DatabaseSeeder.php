<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // The Laravel skeleton seeded a test@example.com / "password" user here.
        // That is no longer safe to leave in: every row in this table can now
        // sign in to the webinar console and read every registrant's contact
        // details, so a well-known throwaway password is a way in, not a
        // convenience. Accounts are real accounts.
        $this->call(AdminUserSeeder::class);
    }
}
