<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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

        $this->registerBladeDirectives();
        $this->registerPolicies();
    }

    /**
     * Register model -> policy mappings for tenant-owned resources.
     *
     * Auto-discovery handles the rest (Laravel 11+ convention:
     * App\Policies\{Model}Policy for App\Models\Tenant\{Model}).
     */
    protected function registerPolicies(): void
    {
        $gate = $this->app->make('gate');

        $gate->policy(\App\Models\Tenant\Course::class, \App\Policies\CoursePolicy::class);
        $gate->policy(\App\Models\Tenant\Enrollment::class, \App\Policies\EnrollmentPolicy::class);
        $gate->policy(\App\Models\Tenant\Quiz::class, \App\Policies\QuizPolicy::class);
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
