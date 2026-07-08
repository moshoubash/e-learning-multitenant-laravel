<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Support\SafeHttp::class,
            fn ($app) => new \App\Support\SafeHttp($app->make(\Illuminate\Http\Client\Factory::class))
        );

        if (! $this->app->environment('production') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
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

        $this->registerBladeDirectives();
        $this->registerPolicies();
        $this->registerSlowQueryLogger();
        $this->registerApiRateLimiter();
    }

    /**
     * Log SQL queries that exceed a configurable threshold to the
     * 'slow_queries' channel. The threshold defaults to 500 ms and
     * can be raised/lowered via SLOW_QUERY_THRESHOLD_MS in .env.
     */
    protected function registerSlowQueryLogger(): void
    {
        $thresholdMs = (float) env('SLOW_QUERY_THRESHOLD_MS', 500);

        DB::listen(function (QueryExecuted $query) use ($thresholdMs) {
            if ($query->time < $thresholdMs) {
                return;
            }

            Log::channel('slow_queries')->warning('Slow query', [
                'connection' => $query->connectionName,
                'time_ms' => $query->time,
                'sql' => $query->sql,
                'bindings' => $query->bindings,
            ]);
        });
    }

    /**
     * Register model -> policy mappings for tenant-owned resources.
     *
     * Uses the Gate facade so we don't depend on the legacy 'gate'
     * string binding that no longer exists in Laravel 11+.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(\App\Models\Tenant\Course::class, \App\Policies\CoursePolicy::class);
        Gate::policy(\App\Models\Tenant\Enrollment::class, \App\Policies\EnrollmentPolicy::class);
        Gate::policy(\App\Models\Tenant\Quiz::class, \App\Policies\QuizPolicy::class);
    }

    /**
     * Configure API rate limiting for the central API routes.
     *
     * Limits authenticated requests to 60 per minute per user,
     * and unauthenticated requests (login) to 10 per minute per IP.
     */
    protected function registerApiRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(60)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('api-auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }

    /**
     * Register Blade directives for RTL-aware spacing helpers.
     *
     * Usage:
     *   @rim('mr-2')     → "mr-2" in LTR, "ml-2" in RTL
     *   @rim('ml-3')     → "ml-3" in LTR, "mr-3" in RTL
     *   @rimauto('text-right') → always flips based on current locale
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('rim', function (string $expression) {
            return "<?php echo \App\Support\SpacingHelper::flipFor($expression, app()->getLocale()); ?>";
        });

        Blade::directive('rimauto', function (string $expression) {
            return "<?php echo \App\Support\SpacingHelper::flipFor($expression, app()->getLocale()); ?>";
        });
    }
}
