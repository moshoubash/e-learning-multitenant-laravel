<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register SetLocale middleware into the web middleware group so
        // the session-stored locale is applied on every web request.
        if (! $this->app->runningInConsole()) {
            $router = $this->app->make('router');
            $router->pushMiddlewareToGroup('web', \App\Http\Middleware\SetLocale::class);
        }
    }
}
