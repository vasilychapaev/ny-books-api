<?php

namespace Tests\Feature\Api;

use App\Services\NytBooksApiService;
use App\Exceptions\ExternalApi\ApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class NytBooksControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Config::set('nyt.api_key', 'test-key');
        // Config::set('nyt.base_url', 'https://api.nytimes.com');
    }

    public function test_history_success()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response(['num_results' => 0, 'results' => ['book1', 'book2']], 200)
        ]);

        $response = $this->getJson('/api/v1/best-sellers/history?title=test&isbn[]=1234567890&offset=0');
        
        $response->assertStatus(200)
            ->assertJson(['num_results' => 0, 'data' => ['book1', 'book2']]);
    }

    public function test_history_validation_errors()
    {
        $response = $this->getJson('/api/v1/best-sellers/history?isbn=invalid&offset=-1');
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['isbn', 'offset']);
    }

    public function test_history_missing_api_key()
    {
        Config::set('nyt.api_key', null);
        
        $response = $this->getJson('/api/v1/best-sellers/history?title=test');
        
        $response->assertStatus(500)
            ->assertJson(['message' => 'NYT API configuration is missing']);
    }

    public function test_history_missing_base_url()
    {
        Config::set('nyt.base_url', null);
        
        $response = $this->getJson('/api/v1/best-sellers/history?title=test');
        
        $response->assertStatus(500)
            ->assertJson(['message' => 'NYT API configuration is missing']);
    }

    // public function test_history_invalid_api_key()
    // {
    //     Http::fake([
    //         'api.nytimes.com/*' => Http::response(['fault' => ['faultstring' => 'Invalid API key']], 401)
    //     ]);

    //     $response = $this->getJson('/api/v1/best-sellers/history?title=test');
        
    //     $response->assertStatus(401)
    //         ->assertJson(['message' => 'NYT API authentication failed']);
    // }

    public function test_history_api_exception()
    {
        $this->mock(NytBooksApiService::class, function ($mock) {
            $mock->shouldReceive('getHistory')
                ->andThrow(new ApiException('API Error', 422));
        });

        $response = $this->getJson('/api/v1/best-sellers/history?title=test');
        
        $response->assertStatus(500)
            ->assertJson(['message' => 'API Error']);
    }
} 