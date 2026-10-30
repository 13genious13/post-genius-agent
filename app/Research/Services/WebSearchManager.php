<?php




namespace App\Research\Services;




use App\Research\Contracts\WebSearchProviderInterface;
use App\Research\DTOs\SearchQuery;
use App\Research\DTOs\SearchResponse;
use App\Research\Providers\TavilySearchProvider;
use InvalidArgumentException;




class WebSearchManager
{
    public function provider(
        ?string $key = null
    ): WebSearchProviderInterface {
        $key ??=
            config(
                'research.provider'
            );




        return match ($key) {
            'tavily' =>
            app(
                TavilySearchProvider::class
            ),




            default =>
            throw new InvalidArgumentException(
                "Unsupported research provider: {$key}"
            ),
        };
    }

    public function search(
        SearchQuery $query,
        ?string $provider = null
    ): SearchResponse {
        return $this
            ->provider($provider)
            ->search($query);
    }
}
