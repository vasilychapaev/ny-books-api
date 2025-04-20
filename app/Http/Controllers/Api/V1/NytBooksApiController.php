<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BestSellersHistoryRequest;
use App\Services\NytBooksApiService;
use Illuminate\Http\JsonResponse;

class NytBooksApiController extends Controller
{
    public function __construct(
        private readonly NytBooksApiService $booksApiService
    ) {}

    public function history(BestSellersHistoryRequest $request): JsonResponse
    {
        $result = $this->booksApiService->getHistory(
            $request->safe()->only('author', 'isbn', 'title', 'offset')
        );
        
        return response()->json($result);
    }

    public function lists(): JsonResponse
    {
        $result = $this->booksApiService->getListsNames();
        return response()->json($result);
    }
} 