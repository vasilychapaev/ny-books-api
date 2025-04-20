<?php

namespace App\Services\ExternalApi;

use App\Contracts\ExternalApi\ApiClientInterface;
use App\Exceptions\ExternalApi\ApiException;
use Illuminate\Support\Facades\Http;

class NytApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly string $apiUrl,
        private readonly string $apiKey
    ) {}

    public function get(string $endpoint, array $params = []): array
    {
        $response = Http::get(
            $this->apiUrl . $endpoint,
            array_merge(['api-key' => $this->apiKey], $params)
        );

        if ($response->status() === 401) {
            throw new ApiException(
                'NYT API authentication failed',
                401,
                $response->json('fault.faultstring')
            );
        }

        if ($response->status() === 400) {
            throw new ApiException(
                'NYT API validation failed',
                400,
                $response->collect('errors')
            );
        }

        if (!$response->successful()) {
            throw new ApiException(
                'NYT API error',
                $response->status(),
                $response->json()
            );
        }

        return $response->json();
    }
} 