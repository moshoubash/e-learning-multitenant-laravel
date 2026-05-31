<?php

use App\Models\Tenant;
use App\Models\Tenant\Course;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Web Routes (Central Application)
|--------------------------------------------------------------------------
|
| These routes are loaded by the web middleware and handle the central
| application. They should NOT be tenant-aware.
|
*/

Route::view('/', 'welcome')->name('home');

Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('web.dashboard');

Route::get('profile', \App\Livewire\Profile::class)
    ->middleware(['auth'])
    ->name('profile');


require __DIR__ . '/auth.php';

Route::get('lang/{lang}', function ($lang) {
    $available = ['en', 'ar'];
    if (! in_array($lang, $available)) {
        abort(404);
    }

    session(['locale' => $lang]);
    return redirect()->back();
})->name('lang.switch');
