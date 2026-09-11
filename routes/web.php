<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\SitemapController as AdminSitemapController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebinarController as AdminWebinarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DemoRequestController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PricingEnquiryController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebinarRegistrationController;
use App\Models\Page;
use App\Models\PageRedirect;
use App\Support\PageRoutes;
use Illuminate\Support\Facades\Route;

Route::post('/demo', [DemoRequestController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('demo.store');

// The "40+ agents" enquiry box on the pricing calculator. Lands in the same
// inbox as demo requests.
Route::post('/enquire', [PricingEnquiryController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('pricing.enquire');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap.xml');
Route::get('/robots.txt', RobotsController::class)->name('robots.txt');

// Filename only — no path traversal, and it is always something this site
// generated (see Admin\MediaController@store), never a name a caller chose.
Route::get('/media/{filename}', [MediaController::class, 'show'])
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('media.uploaded');

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
| from this console's own Users screen (or `php artisan corex:admin`).
*/
// The sign-in lives at the root — /login — because that is the address people
// are given and the one they will type. It is named `login`, which is also the
// name Laravel's own auth plumbing looks for when it turns a guest away.
Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// The console used to sign in at /admin/login. Anyone holding that bookmark is
// sent on rather than shown a 404.
Route::redirect('/admin/login', '/login');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');

    Route::get('/marketing', [SiteSettingController::class, 'edit'])->name('marketing.edit');
    Route::put('/marketing', [SiteSettingController::class, 'update'])->name('marketing.update');

    Route::get('/sitemap', [AdminSitemapController::class, 'index'])->name('sitemap');

    Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');

    Route::resource('users', UserController::class)->except('show');

    Route::prefix('webinars')->name('webinars.')->where(['slug' => '[A-Za-z0-9._-]+'])->group(function () {
        Route::get('/', [AdminWebinarController::class, 'index'])->name('index');

        // Before /{slug}, or the binding swallows it.
        Route::get('/create', [AdminWebinarController::class, 'create'])->name('create');
        Route::post('/', [AdminWebinarController::class, 'store'])->name('store');

        Route::get('/{slug}/edit', [AdminWebinarController::class, 'edit'])->name('edit');
        Route::put('/{slug}', [AdminWebinarController::class, 'update'])->name('update');
        Route::delete('/{slug}', [AdminWebinarController::class, 'destroy'])->name('archive');

        Route::get('/{slug}/registrations', [RegistrationController::class, 'index'])->name('registrations');

        // Paste the joining link and email it to everyone already signed up.
        // POST because it sends mail that cannot be unsent.
        Route::post('/{slug}/join-link', [RegistrationController::class, 'sendJoinLink'])->name('join-link');

        // The reason this screen exists. `zoom` imports straight into a
        // Zoom webinar's registrant list; `full` is the sales follow-up.
        Route::get('/{slug}/registrations/{format}.csv', [RegistrationController::class, 'download'])
            ->where('format', 'zoom|full')
            ->name('registrations.download');
    });
});

/*
|--------------------------------------------------------------------------
| Database-slug-driven public routing
|--------------------------------------------------------------------------
|
| Every static page (home, pricing, mobile-app, contact, …) is registered
| below from its `pages` row rather than hardcoded, so renaming a slug from
| the admin panel changes the live URL without a deploy. See
| App\Support\PageRoutes for the key => controller-action map and the
| reserved-slug list that keeps a page from ever shadowing a route above.
|
| Wrapped in try/catch: a fresh install that has not run migrations yet must
| still be able to register routes (composer's post-install autoload dump
| boots the framework), so a missing `pages` table falls back to registering
| every page at its key instead of crashing route registration outright.
*/
try {
    $pages = Page::query()->get()->keyBy('key');
} catch (Throwable) {
    $pages = collect();
}

foreach (PageRoutes::actions() as $key => $action) {
    $slug = $pages->get($key)?->slug ?? $key;

    Route::get(PageRoutes::pathFor($key, $slug), $action)->name($key);
}

// The contact page's POST handler lives at the same address as the page
// itself, whatever that currently is.
$contactSlug = $pages->get('contact')?->slug ?? 'contact';
Route::post('/'.ltrim($contactSlug, '/'), [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

try {
    $redirects = PageRedirect::query()->with('page')->get();
} catch (Throwable) {
    $redirects = collect();
}

foreach ($redirects as $redirect) {
    $targetSlug = $redirect->page?->slug;

    if ($targetSlug === null) {
        continue;
    }

    $targetPath = PageRoutes::pathFor($redirect->page->key, $targetSlug);

    Route::get('/'.ltrim($redirect->old_slug, '/'), fn () => redirect($targetPath, $redirect->status_code));
}
