<?php

namespace Tests\Unit\Services;

use App\Exceptions\ExternalApi\ApiException;
use App\Services\ExternalApi\NytApiClient;
use App\Services\NytBooksApiService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class NytBooksApiServiceTest extends TestCase
{
    private NytBooksApiService $service;
    private NytApiClient $apiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiClient = $this->createMock(NytApiClient::class);
        $this->service = new NytBooksApiService($this->apiClient);
    }

    // public function test_history_returns_cached_data()
    // {
    //     $params = ['title' => 'test'];
    //     $expectedResponse = ['num_results' => 0, 'data' => []];
        
    //     Cache::shouldReceive('remember')
    //         ->once()
    //         ->with('nyt_history_' . md5(json_encode($params)), config('nyt.cache.ttl'), \Closure::class)
    //         ->andReturn($expectedResponse);

    //     $result = $this->service->getHistory($params);
        
    //     $this->assertEquals($expectedResponse, $result);
    // }

    public function test_history_passes_params_to_api_client()
    {
        $params = ['title' => 'test', 'isbn' => '1234567890'];
        
        $this->apiClient->expects($this->once())
            ->method('get')
            ->with('lists/best-sellers/history.json', $params)
            ->willReturn(['results' => []]);

        $this->service->getHistory($params);
    }

    public function test_history_handles_empty_response()
    {
        $this->apiClient->expects($this->once())
            ->method('get')
            ->willReturn(['results' => []]);

        $result = $this->service->getHistory(['title' => 'test']);
        
        $this->assertEquals(['num_results' => 0, 'data' => []], $result);
    }

    public function test_search_throws_api_exception()
    {
        $this->expectException(ApiException::class);
        
        $this->apiClient->expects($this->once())
            ->method('get')
            ->willThrowException(new ApiException('API Error', 422));

        $this->service->getHistory(['title' => 'test']);
    }
} 