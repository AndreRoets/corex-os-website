<?php

use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\WebinarController as AdminWebinarController;
use App\Http\Controllers\DemoRequestController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WebinarRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

Route::get('/mobile-app', [PageController::class, 'mobileApp'])->name('mobile-app');

Route::post('/demo', [DemoRequestController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('demo.store');

/*
|--------------------------------------------------------------------------
| Webinars — public registration
|--------------------------------------------------------------------------
|
| The slug is CoreX's, and the link the team hands out. Everything on these
| pages is fetched from CoreX per request and nothing is stored here.
|
| The slug pattern is deliberately tight: it is interpolated into the CoreX
| URL, so a permissive one would let a crafted path walk out of the intended
| endpoint and into another part of that API, carrying our token with it.
*/
Route::prefix('webinars')->name('webinars.')->where(['slug' => '[A-Za-z0-9._-]+'])->group(function () {
    Route::get('/{slug}', [WebinarRegistrationController::class, 'show'])->name('show');

    Route::get('/{slug}/thank-you', [WebinarRegistrationController::class, 'thanks'])->name('thanks');

    // Rate limited because it makes an outbound call and sends an email on our
    // behalf. CoreX throttles repeat registrations too, but that is a courtesy
    // to the person who double-clicked, not a defence against a script.
    Route::post('/{slug}', [WebinarRegistrationController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register');
});

/*
|--------------------------------------------------------------------------
| Admin console
|--------------------------------------------------------------------------
|
| Session-authenticated, and the ONLY place registrant data appears. There is
| no self-registration and no emailed password reset — accounts are created
| with `php artisan corex:admin`.
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [SessionController::class, 'create'])->name('login');
        Route::post('/login', [SessionController::class, 'store'])->name('login.store');
    });

    Route::post('/logout', [SessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::middleware('auth')->group(function () {
        Route::prefix('webinars')->name('webinars.')->where(['slug' => '[A-Za-z0-9._-]+'])->group(function () {
            Route::get('/', [AdminWebinarController::class, 'index'])->name('index');

            // Before /{slug}, or the binding swallows it.
            Route::get('/create', [AdminWebinarController::class, 'create'])->name('create');
            Route::post('/', [AdminWebinarController::class, 'store'])->name('store');

            Route::get('/{slug}/edit', [AdminWebinarController::class, 'edit'])->name('edit');
            Route::put('/{slug}', [AdminWebinarController::class, 'update'])->name('update');
            Route::delete('/{slug}', [AdminWebinarController::class, 'destroy'])->name('archive');

            Route::get('/{slug}/registrations', [RegistrationController::class, 'index'])->name('registrations');

            // The reason this screen exists. `zoom` imports straight into a
            // Zoom webinar's registrant list; `full` is the sales follow-up.
            Route::get('/{slug}/registrations/{format}.csv', [RegistrationController::class, 'download'])
                ->where('format', 'zoom|full')
                ->name('registrations.download');
        });
    });
});
