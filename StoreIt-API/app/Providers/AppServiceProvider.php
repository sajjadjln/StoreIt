<?php

namespace App\Providers;

use App\Contracts\AuthContract;
use App\Contracts\TokenManagerContract;
use App\Services\SanctumTokenManagerService;
use Illuminate\Support\ServiceProvider;
use App\Services\AuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthContract::class, AuthService::class);
        $this->app->singleton(TokenManagerContract::class, SanctumTokenManagerService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
