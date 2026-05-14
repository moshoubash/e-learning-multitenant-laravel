<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\ScopeSessions;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| These routes are loaded via bootstrap/app.php when a tenant
| is identified via the domain/subdomain.
|
*/

// Tenant Home / Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth:tenant'])->name('tenant.dashboard');

Route::get('/', function () {
    return redirect()->route('tenant.dashboard');
});

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth:tenant'])->name('tenant.profile');

// Auth routes for tenant
require __DIR__ . '/auth.php';