<?php

namespace App\Services;

use App\Contracts\ExternalApi\ApiClientInterface;

class NytBooksApiService
{
    public function __construct(
        private readonly ApiClientInterface $apiClient
    ) {}

    public function getHistory(array $params): array
    {
        $response = $this->apiClient->get(
            config('nyt.endpoints.best_sellers_history'),
            $params
        );

        return [
            'num_results' => $response['num_results'] ?? 0,
            'data' => $response['results'] ?? [],
        ];
    }

    public function getListsNames(): array
    {
        $response = $this->apiClient->get(
            config('nyt.endpoints.lists_names')
        );

        return [
            'num_results' => $response['num_results'] ?? 0,
            'data' => $response['results'] ?? [],
        ];
    }
} 