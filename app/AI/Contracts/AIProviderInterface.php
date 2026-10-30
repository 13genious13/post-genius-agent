<?php

namespace App\AI\Contracts;

use App\AI\DTOs\AIRequest;
use App\AI\DTOs\AIResponse;

interface AIProviderInterface
{
    public function key(): string;

    public function chat(
        AIRequest $request
    ): AIResponse;

    public function testConnection(): array;
}
