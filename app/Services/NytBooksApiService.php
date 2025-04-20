<?php

namespace App\Services;

use App\Contracts\ExternalApi\ApiClientInterface;
use Illuminate\Support\Facades\Cache;

class NytBooksApiService
{
    public function __construct(
        private readonly ApiClientInterface $apiClient
    ) {}

    public function getHistory(array $params): array
    {
        $cacheKey = 'nyt_history_' . md5(json_encode($params));
        
        return Cache::remember($cacheKey, config('nyt.cache.ttl'), function () use ($params) {
            $response = $this->apiClient->get(
                config('nyt.endpoints.best_sellers_history'),
                $params
            );

            return [
                'num_results' => $response['num_results'] ?? 0,
                'data' => $response['results'] ?? [],
            ];
        });
    }

    public function getListsNames(): array
    {
        return Cache::remember('nyt_lists_names', config('nyt.cache.ttl'), function () {
            $response = $this->apiClient->get(
                config('nyt.endpoints.lists_names')
            );

            return [
                'num_results' => $response['num_results'] ?? 0,
                'data' => $response['results'] ?? [],
            ];
        });
    }
} 