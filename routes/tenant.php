<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
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

Route::get('/', function () {
    return redirect()->route('tenant.dashboard');
});

Route::middleware(['auth:tenant'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('tenant.dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('tenant.profile');

    Route::livewire('/admin/users', 'admin.users')->name('tenant.admin.users');
    Route::livewire('/admin/tenantSettings', 'admin.tenant-settings')->name('tenant.admin.tenant-settings');
    Route::livewire('/admin/courses', 'admin.courses')->name('tenant.admin.courses');
});

// Auth routes for tenant
require __DIR__ . '/auth.php';