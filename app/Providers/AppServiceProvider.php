<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\FooterSetting;
use App\Models\WebsiteSetting;
use App\Providers\HelperServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register our HelperServiceProvider
        $this->app->register(HelperServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $footerSettings = FooterSetting::query()->latest('id')->first();
        } catch (\Throwable $e) {
            $footerSettings = null;
        }
        View::share('footerSettings', $footerSettings);

        try {
            $websiteSettings = WebsiteSetting::first();
        } catch (\Throwable $e) {
            $websiteSettings = null;
        }
        View::share('websiteSettings', $websiteSettings);
    }
}
