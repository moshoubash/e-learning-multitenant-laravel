<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

    });
}

require __DIR__ . '/auth.php';
