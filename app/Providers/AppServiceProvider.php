<?php

namespace App\Providers;

use App\Libraries\ConnectToService;
use App\Libraries\smsServiceProviders\KavehNegar;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('connectToService', function () {
            $connection = new ConnectToService();
            $connection->smsProvider = new KavehNegar();
            return $connection;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
