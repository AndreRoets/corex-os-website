<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * The CoreX team's login to this website's admin console.
 *
 * This is entirely separate from how the website authenticates to CoreX. That
 * is a pair of bearer tokens in the environment, used server-side only. This is
 * an ordinary session login, and the two must never be conflated: a person
 * signing in here is being given the right to *ask us* to use those tokens on
 * their behalf, not the tokens themselves.
 *
 * There is no self-registration and no emailed password reset. Accounts are
 * created from the command line by whoever administers the site
 * (`php artisan corex:admin`). A public reset form on a console that exposes a
 * registrant list is an invitation: it turns "know an admin's email address"
 * into a foothold, and there is no support desk here to notice.
 */
class SessionController extends Controller
{
    /**
     * Five attempts per fifteen minutes, keyed on the email *and* the IP.
     *
     * Keyed on both because either alone is the wrong shape: on IP alone one
     * office behind a single NAT locks itself out, and on email alone anyone
     * who knows an admin address can lock that admin out from anywhere.
     */
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 15 * 60;

    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.webinars.index');
        }

        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $key = $this->throttleKey($request, $credentials['email']);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Try again in '.ceil($seconds / 60).' minute(s).',
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, self::DECAY_SECONDS);

            // Log the attempt so a slow guessing campaign is visible in the log,
            // but never the password — not even a truncated one.
            Log::warning('Admin login failed', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
            ]);

            // One message for both a wrong password and an unknown address.
            // Distinguishing them tells an attacker which addresses are real.
            throw ValidationException::withMessages([
                'email' => 'Those details do not match an account.',
            ]);
        }

        RateLimiter::clear($key);

        // A fresh session ID on login, so a session token an attacker planted
        // beforehand does not become an authenticated one.
        $request->session()->regenerate();

        return redirect()->intended(route('admin.webinars.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate rather than just forget: the registrant lists this console
        // renders are personal data, and stale session data behind a shared
        // machine's back button is exactly what we do not want left lying about.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been signed out.');
    }

    private function throttleKey(Request $request, string $email): string
    {
        return 'admin-login|'.Str::lower($email).'|'.$request->ip();
    }
}
