<?php

return [
    'base_url' => env('NYT_API_URL'),
    'api_key' => env('NYT_API_KEY'),
    
    'endpoints' => [
        'best_sellers_history' => 'lists/best-sellers/history.json',
        'lists_names' => 'lists/names.json',
    ],
    'cache' => [
        'ttl' => env('NYT_CACHE_TTL', 3600), // 1 hour
    ],
]; 