<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\FooterSetting;

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
        try {
            $footerSettings = FooterSetting::query()->latest('id')->first();
        } catch (\Throwable $e) {
            $footerSettings = null;
        }
        View::share('footerSettings', $footerSettings);
    }
}
