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
            $baseUrl = config('nyt.base_url');
            $apiKey = config('nyt.api_key');

            if (!$baseUrl || !$apiKey) {
                throw new \RuntimeException('NYT API configuration is missing');
            }

            return new NytApiClient($baseUrl, $apiKey);
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
