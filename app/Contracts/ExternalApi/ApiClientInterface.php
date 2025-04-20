<?php

namespace App\Contracts\ExternalApi;

interface ApiClientInterface
{
    public function get(string $endpoint, array $params = []): array;
} 