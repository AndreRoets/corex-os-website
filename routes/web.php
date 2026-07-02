<?php

use App\Http\Controllers\DemoRequestController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::post('/demo', [DemoRequestController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('demo.store');
