<?php

namespace App\AI\DTOs;

class AIResponse
{
    public function __construct(
        public string $content,
        public string $provider,
        public ?string $model = null,
        public ?int $promptTokens = null,
        public ?int $completionTokens = null,
        public ?int $totalTokens = null,
        public ?int $latencyMs = null,
        public array $toolCalls = [],
        public array $raw = [],
    ) {
    }

    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }

    public function json(): ?array
    {
        $decoded = json_decode(
            $this->content,
            true
        );

        return json_last_error()
        === JSON_ERROR_NONE
            ? $decoded
            : null;
    }
}
