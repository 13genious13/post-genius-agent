<?php

namespace App\Research\Contracts;

use App\Research\DTOs\SearchQuery;
use App\Research\DTOs\SearchResponse;

interface WebSearchProviderInterface
{
    public function key(): string;
    public function search(SearchQuery $query): SearchResponse;
    public function testConnection(): array;
}
