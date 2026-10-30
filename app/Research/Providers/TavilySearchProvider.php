<?php




namespace App\Research\Providers;




use App\Research\Contracts\WebSearchProviderInterface;
use App\Research\DTOs\SearchQuery;
use App\Research\DTOs\SearchResponse;
use App\Research\DTOs\SearchResult;
use Illuminate\Support\Facades\Http;
use RuntimeException;




class TavilySearchProvider implements WebSearchProviderInterface
{
    public function key(): string
    {
        return 'tavily';
    }




    public function search(
        SearchQuery $query
    ): SearchResponse {
        $config = config(
            'research.providers.tavily'
        );




        $apiKey =
            $config['api_key'] ?? null;




        if (!$apiKey) {
            throw new RuntimeException(
                'Tavily API key is not configured.'
            );
        }




        $baseUrl = rtrim(
            $config['base_url'],
            '/'
        );




        $started =
            microtime(true);




        $response = Http::withToken(
            $apiKey
        )
            ->acceptJson()
            ->asJson()
            ->timeout(
                $config['timeout'] ?? 45
            )
            ->connectTimeout(10)
            ->retry(
                2,
                750,
                throw: false
            )
            ->post(
                $baseUrl.'/search',
                [
                    'query' =>
                        $query->query,




                    'search_depth' =>
                        $config[
                        'search_depth'
                        ] ?? 'advanced',




                    'max_results' =>
                        min(
                            $query->maxResults,
                            10
                        ),




                    'include_answer' =>
                        false,




                    'include_raw_content' =>
                        true,
                ]
            );




        $latency =
            (int) round(
                (
                    microtime(true)
                    - $started
                ) * 1000
            );




        if (!$response->successful()) {
            throw new RuntimeException(
                'Web research provider returned HTTP '
                .$response->status().'.'
            );
        }




        $json = $response->json();




        $results = collect(
            $json['results'] ?? []
        )
            ->map(
                function (array $item) {
                    $content =
                        $item[
                        'raw_content'
                        ]
                        ?? $item[
                        'content'
                        ]
                        ?? null;




                    return new SearchResult(
                        url:
                        $item['url'] ?? '',




                        title:
                        $item['title']
                        ?? null,




                        content:
                        $content,




                        snippet:
                        $item['content']
                        ?? null,




                        score:
                        isset(
                            $item['score']
                        )
                            ? (float)
                        $item['score']
                            : null,




                        metadata: [
                            'published_date' =>
                                $item[
                                'published_date'
                                ] ?? null,
                        ],
                    );
                }
            )
            ->filter(
                fn (SearchResult $result) =>
                filled($result->url)
            )
            ->values()
            ->all();




        return new SearchResponse(
            provider:
            $this->key(),




            query:
            $query->query,




            results:
            $results,




            answer:
            $json['answer']
            ?? null,




            latencyMs:
            $latency,




            raw: [],
        );
    }




    public function testConnection(): array
    {
        try {
            $response =
                $this->search(
                    new SearchQuery(
                        query:
                        'artificial intelligence marketing',
                        maxResults: 1,
                    )
                );




            return [
                'success' => true,
                'message' =>
                    'Research provider connected successfully.',
                'provider' =>
                    $response->provider,
            ];




        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' =>
                    $e->getMessage(),
            ];
        }
    }
}
