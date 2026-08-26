<?php

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
        // There is no public "login" route on this site — the only sign-in is
        // the webinar admin console. Without this, a logged-out request to an
        // admin URL dies looking for a route named `login` instead of showing
        // the sign-in page, which reads as a broken site rather than a locked
        // door.
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        $middleware->redirectUsersTo(fn () => route('admin.webinars.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
