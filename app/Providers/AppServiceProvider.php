<?php

namespace App\Providers;

use App\Libraries\ConnectToService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ConnectToService::class, function (Application $app) {
            return new ConnectToService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
