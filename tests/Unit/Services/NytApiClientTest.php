<?php

namespace Tests\Unit\Services;

use App\Exceptions\ExternalApi\ApiException;
use App\Services\ExternalApi\NytApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class NytApiClientTest extends TestCase
{
    private NytApiClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        // Config::set('nyt.api_key', 'test-key');
        // Config::set('nyt.base_url', 'https://api.nytimes.com');
        $this->client = new NytApiClient(Config::get('nyt.base_url'), Config::get('nyt.api_key'));
    }

    public function test_get_success()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response(['results' => ['data']], 200)
        ]);

        $response = $this->client->get('test/endpoint', ['param' => 'value']);
        
        $this->assertEquals(['results' => ['data']], $response);
    }

    public function test_get_unauthorized()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response(['message' => 'NYT API authentication failed'], 401)
        ]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('NYT API authentication failed');
        
        $this->client->get('test/endpoint');
    }

    public function test_get_bad_request()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response(['errors' => ['Invalid parameter']], 400)
        ]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('NYT API validation failed');
        
        $this->client->get('test/endpoint');
    }

    public function test_get_server_error()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response(['message' => 'Server error'], 500)
        ]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('NYT API error');
        
        $this->client->get('test/endpoint');
    }

    // public function test_get_missing_api_key()
    // {
    //     Config::set('nyt.api_key', null);
        
    //     $this->expectException(ApiException::class);
    //     $this->expectExceptionMessage('NYT API key is not configured');
        
    //     $this->client->get(config('nyt.endpoints.best_sellers_history'));
    // }

    public function test_get_missing_base_url()
    {
        Config::set('nyt.base_url', null);
        
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('NYT API validation failed');
        
        $this->client->get('test/endpoint');
    }
} 