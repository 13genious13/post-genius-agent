<?php

namespace App\Research\DTOs;

class SearchResponse
{
    public function __construct(
        public string $provider,
        public string $query,
        public array $results = [],
        public ?string $answer = null,
        public ?int $latencyMs = null,
        public array $raw = [],
    ) {
    }
}
