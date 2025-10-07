<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\DateHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the DateHelper as a singleton
        $this->app->singleton('datehelper', function ($app) {
            return new DateHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add a Blade directive for formatting dates
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDate($expression); ?>";
        });
        
        Blade::directive('formatDateTime', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDateTime($expression); ?>";
        });
        
        Blade::directive('formatDateTime12Hour', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDateTime12Hour($expression); ?>";
        });
        
        Blade::directive('formatDateOnly', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDateOnly($expression); ?>";
        });
    }
}
