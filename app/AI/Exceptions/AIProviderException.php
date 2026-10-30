<?php

namespace App\AI\Exceptions;

use RuntimeException;
use Throwable;

class AIProviderException extends RuntimeException
{
    public function __construct(
        string $message,
        public ?string $provider = null,
        public ?int $statusCode = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            $message,
            $statusCode ?? 0,
            $previous
        );
    }
}
