<?php

namespace App\Research\DTOs;

class SearchQuery
{
    public function __construct(
        public string $query,
        public string $category = 'general',
        public ?string $purpose = null,
        public int $maxResults = 6,
    ) {
    }
}
