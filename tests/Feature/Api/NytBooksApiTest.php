<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Services\NytBooksApiService;


class NytBooksApiTest extends TestCase
{
    protected $apiUrl;
    protected $apiKey;

    protected function setUp(): void 
    {
        parent::setUp();

        $this->apiUrl = Config::get('nyt.base_url');
        $this->apiKey = 'valid';

        Config::set('nyt.api_key', $this->apiKey);

        Http::fake([
            "{$this->apiUrl}?api-key=aninvalidapikey" => Http::response(
                json_decode(file_get_contents('tests/stubs/nyt_api_search_invalid_api_key_status_401.json'), true),
                401
            ),
        
            "{$this->apiUrl}?api-key=avalidapikey" => Http::response(
                json_decode(file_get_contents('tests/stubs/nyt_api_search_status_200.json'), true),
                200
            ),
        
            "{$this->apiUrl}?api-key=avalidapikey&title=book" => Http::response(
                json_decode(file_get_contents('tests/stubs/nyt_api_search_title_book_status_200.json'), true),
                200
            ),
        
            "{$this->apiUrl}?api-key=avalidapikey&isbn=0399178570" => Http::response(
                json_decode(file_get_contents('tests/stubs/nyt_api_search_isbn_status_200.json'), true),
                200
            ),
        
            "{$this->apiUrl}?api-key=avalidapikey&offset=40" => Http::response(
                json_decode(file_get_contents('tests/stubs/nyt_api_search_offset_40_status_200.json'), true),
                200
            ),
        ]);

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Config::set('nyt.api_key', null);
    }

    public function test_the_history_route_exists(): void
    {
        $this->mock(NytBooksApiService::class, function ($mock) {
            $mock->shouldReceive('getHistory')
                ->once()
                ->with([])
                ->andReturn([
                    'num_results' => 2,
                    'results' => ['book1', 'book2']
                ]);
        });

        $response = $this->get('/api/v1/best-sellers/history');

        $response->assertOk();
        $response->assertJsonCount(2, 'results');
    }

    
}
