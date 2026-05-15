<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

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

    Route::livewire('/admin/users', 'admin.users')->middleware('role:admin')->name('tenant.admin.users');
    Route::livewire('/admin/courses', 'admin.courses')->middleware('role:instructor')->name('tenant.instructor.courses');
});

// Auth routes for tenant
require __DIR__ . '/auth.php';