<?php

namespace App\Exceptions\ExternalApi;

class ApiException extends \Exception
{
    public function __construct(
        string $message,
        private readonly int $statusCode,
        private readonly mixed $errors = null
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): mixed
    {
        return $this->errors;
    }
} 