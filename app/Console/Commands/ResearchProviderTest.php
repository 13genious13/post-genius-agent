<?php




namespace App\Console\Commands;




use App\Research\DTOs\SearchQuery;
use App\Research\Services\WebSearchManager;
use Illuminate\Console\Command;




class ResearchProviderTest extends Command
{
    protected $signature =
        'research:test {query=AI marketing trends}';




    protected $description =
        'Test the configured live research provider';




    public function handle(
        WebSearchManager $manager
    ): int {
        try {
            $response =
                $manager->search(
                    new SearchQuery(
                        query:
                        $this->argument(
                            'query'
                        ),




                        maxResults: 3,
                    )
                );




            $this->info(
                'Provider: '
                .$response->provider
            );




            $this->info(
                'Latency: '
                .$response->latencyMs
                .' ms'
            );




            foreach (
                $response->results
                as $index => $result
            ) {
                $this->newLine();




                $this->line(
                    ($index + 1)
                    .'. '
                    .$result->title
                );




                $this->line(
                    $result->url
                );
            }




            return self::SUCCESS;




        } catch (\Throwable $e) {
            $this->error(
                $e->getMessage()
            );




            return self::FAILURE;
        }
    }
}
