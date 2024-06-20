<?php

namespace App\Providers;

use App\Services\APIClient\Impl\EmsifaAPIService;
use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\Impl\PemohonServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function provides()
    {
        return [
            RegionAPIService::class,
            PemohonService::class
        ];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RegionAPIService::class, EmsifaAPIService::class);
        $this->app->singleton(PemohonService::class, PemohonServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
