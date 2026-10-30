<?php

namespace App\AI\DTOs;

class AIRequest
{
    public function __construct(
        public array $messages,
        public ?string $model = null,
        public ?float $temperature = null,
        public ?int $maxTokens = null,
        public ?array $tools = null,
        public ?string $feature = null,
        public ?int $userId = null,
        public ?int $businessId = null,
        public array $metadata = [],
        public bool $jsonMode = false,
    ) {
    }
}
