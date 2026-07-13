<?php

use App\Http\Controllers\DemoRequestController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

Route::get('/mobile-app', [PageController::class, 'mobileApp'])->name('mobile-app');

Route::post('/demo', [DemoRequestController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('demo.store');
