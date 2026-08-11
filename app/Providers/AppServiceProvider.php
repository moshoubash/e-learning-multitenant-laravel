<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use App\Helpers\LocalizationHelper;

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

        // Register Blade directives for localization
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives for localization
     */
    private function registerBladeDirectives(): void
    {
        // @trans('auth.email') - Get localized text
        Blade::directive('trans', function ($key) {
            return "<?php echo LocalizationHelper::get({$key}); ?>";
        });

        // @transAr('auth.email') - Get Arabic text
        Blade::directive('transAr', function ($key) {
            return "<?php echo LocalizationHelper::transAr({$key}); ?>";
        });

        // @transEn('auth.email') - Get English text
        Blade::directive('transEn', function ($key) {
            return "<?php echo LocalizationHelper::transEn({$key}); ?>";
        });

        // @isArabic ... @endIsArabic - Conditional Arabic
        Blade::if('isArabic', function () {
            return LocalizationHelper::isArabic();
        });

        // @isEnglish ... @endIsEnglish - Conditional English
        Blade::if('isEnglish', function () {
            return LocalizationHelper::isEnglish();
        });

        // @textDir - Get text direction (rtl/ltr)
        Blade::directive('textDir', function () {
            return "<?php echo LocalizationHelper::getTextDirection(); ?>";
        });

        // @htmlLang - Get HTML lang attribute
        Blade::directive('htmlLang', function () {
            return "<?php echo LocalizationHelper::getHtmlLang(); ?>";
        });
    }
}
