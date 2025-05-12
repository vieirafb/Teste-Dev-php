<?php

namespace App\Providers;

use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\ZipCodeFinder;
use App\Integrations\ViaCepApi;
use App\Repositories\Eloquent\CustomerRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(ZipCodeFinder::class, ViaCepApi::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
