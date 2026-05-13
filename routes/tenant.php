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
| These routes are loaded by the TenancyServiceProvider when a tenant
| is identified via the domain/subdomain. The middleware stack ensures
| proper tenant isolation.
|
*/

Route::middleware([
    InitializeTenancyByDomain::class,
    'web',
        // PreventAccessFromCentralDomains::class,
    ScopeSessions::class
])->group(function () {
    // Tenant Home / Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth:tenant', 'verified'])->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Auth routes for tenant
    require __DIR__ . '/auth.php';
});