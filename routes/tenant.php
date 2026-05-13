<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

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
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Tenant Dashboard
    Route::get('/', function () {
        $tenant = tenant();

        return response()->json([
            'status' => 'success',
            'message' => 'This is your multi-tenant application.',
            'tenant_id' => $tenant->id ?? null,
            'data' => $tenant->data ?? null,
        ]);
    })->name('tenant.home');

    // Auth routes for tenant - using tenant-aware auth
    require __DIR__ . '/auth.php';
});
