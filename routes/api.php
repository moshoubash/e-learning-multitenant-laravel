<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Central Application)
|--------------------------------------------------------------------------
|
| These routes are loaded with the 'api' middleware group and handle
| the central application API. They are NOT tenant-aware.
|
| Authentication is via Laravel Sanctum personal access tokens. Clients
| obtain a token via POST /api/auth/login, then send
| Authorization: Bearer <token> on subsequent requests.
|
*/

Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login')->middleware('throttle:api-auth');

// Tenant Management API (Central) — requires a Sanctum-issued bearer token
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');

    Route::prefix('tenants')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('tenants.index');
        Route::post('/', [TenantController::class, 'store'])->name('tenants.store');
        Route::get('/{id}', [TenantController::class, 'show'])->name('tenants.show');
        Route::put('/{id}', [TenantController::class, 'update'])->name('tenants.update');
        Route::patch('/{id}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
        Route::patch('/{id}/deactivate', [TenantController::class, 'deactivate'])->name('tenants.deactivate');
        Route::delete('/{id}', [TenantController::class, 'destroy'])->name('tenants.destroy');
    });
});
