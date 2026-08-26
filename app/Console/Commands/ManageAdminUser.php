<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password as promptPassword;
use function Laravel\Prompts\text;

/**
 * Create or update an admin account for the webinar console.
 *
 * This is the only way an account comes into existence. There is no
 * self-registration form and no emailed password reset, because the console
 * behind it can read every registrant's name, company, email and phone number.
 * Anyone who can run this command already has the server.
 */
class ManageAdminUser extends Command
{
    protected $signature = 'corex:admin
                            {email? : The account email address}
                            {--name= : Display name}
                            {--password= : Set non-interactively (avoid — it lands in shell history)}';

    protected $description = 'Create an admin account for the webinar console, or reset its password';

    public function handle(): int
    {
        $email = $this->argument('email') ?: text(
            label: 'Email address',
            required: true,
        );

        $existing = User::where('email', $email)->first();

        $name = $this->option('name')
            ?: $existing?->name
            ?: text(label: 'Name', default: 'CoreX Admin', required: true);

        $password = $this->option('password') ?: promptPassword(
            label: $existing ? 'New password' : 'Password',
            required: true,
        );

        $validator = Validator::make(
            ['email' => $email, 'name' => $name, 'password' => $password],
            [
                'email' => ['required', 'email', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                // Long rather than gnarly. A memorable passphrase that nobody
                // needs to write on a sticky note beats eight characters of
                // punctuation that somebody will.
                'password' => ['required', 'string', 'min:12'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        // `password` is cast to `hashed` on the model, so this is bcrypt at the
        // configured cost — never a plain string, even for a moment.
        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => $password],
        );

        $this->components->info(
            $existing
                ? "Password reset for {$user->email}."
                : "Admin account created for {$user->email}."
        );

        if ($this->option('password')) {
            $this->components->warn('The password was passed on the command line and is now in your shell history.');
        }

        $this->line('  Sign in at: '.route('admin.login'));

        return self::SUCCESS;
    }
}
