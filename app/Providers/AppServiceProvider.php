<?php

namespace App\Providers;

use App\Contracts\ExternalApi\ApiClientInterface;
use App\Services\ExternalApi\NytApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApiClientInterface::class, function ($app) {
            return new NytApiClient(
                config('nyt.base_url'),
                config('nyt.api_key')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
