<?php

namespace App\Exceptions;

use App\Exceptions\ExternalApi\ApiException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [];

    public function register(): void
    {
        $this->renderable(function (ApiException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ], $e->getStatusCode());
        });
    }
} 