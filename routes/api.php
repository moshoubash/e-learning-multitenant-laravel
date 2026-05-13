<?php

use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Central Application)
|--------------------------------------------------------------------------
|
| These routes are loaded by the api middleware and handle the central
| application API. They should NOT be tenant-aware.
|
*/

// Tenant Management API (Central)
Route::prefix('tenants')->group(function () {
    Route::get('/', [TenantController::class, 'index'])->name('tenants.index');
    Route::post('/', [TenantController::class, 'store'])->name('tenants.store');
    Route::get('/{id}', [TenantController::class, 'show'])->name('tenants.show');
    Route::put('/{id}', [TenantController::class, 'update'])->name('tenants.update');
    Route::delete('/{id}', [TenantController::class, 'destroy'])->name('tenants.destroy');
});
