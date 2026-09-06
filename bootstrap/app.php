<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Where a logged-out visitor to an admin URL is sent. Stated explicitly
        // rather than left to the default so that renaming the route can never
        // turn a locked door into a 500.
        $middleware->redirectGuestsTo(fn () => route('login'));

        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));

        // Every admin screen is an Inertia page; the public marketing pages stay
        // Blade and simply ignore the shared props this adds.
        $middleware->web(append: [HandleInertiaRequests::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
