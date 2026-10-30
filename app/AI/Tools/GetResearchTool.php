<?php




namespace App\AI\Tools;




use App\AI\Tools\Contracts\AgentToolInterface;
use App\Models\AgentRun;
use RuntimeException;




class GetResearchTool implements AgentToolInterface
{
    public function key(): string
    {
        return 'get_research';
    }




    public function name(): string
    {
        return 'Get Campaign Research';
    }




    public function description(): string
    {
        return 'Returns completed campaign research and evidence sources.';
    }




    public function execute(
        AgentRun $run,
        array $arguments = []
    ): array {
        $research =
            $run->researchRun;




        if (!$research) {
            throw new RuntimeException(
                'No research run is attached.'
            );
        }




        if (
            $research->status !==
            'completed'
        ) {
            throw new RuntimeException(
                'Research is not completed.'
            );
        }




        $research->load('sources');




        return [
            'id' => $research->id,
            'version' =>
                $research->version,
            'executive_summary' =>
                $research->executive_summary,
            'market_insights' =>
                $research->market_insights ?? [],
            'audience_insights' =>
                $research->audience_insights ?? [],
            'competitor_insights' =>
                $research->competitor_insights ?? [],
            'trend_insights' =>
                $research->trend_insights ?? [],
            'opportunities' =>
                $research->opportunities ?? [],
            'risks' =>
                $research->risks ?? [],




            'sources' =>
                $research
                    ->sources
                    ->map(fn ($source) => [
                        'id' => $source->id,
                        'title' =>
                            $source->title,
                        'domain' =>
                            $source->domain,
                        'url' =>
                            $source->url,
                    ])
                    ->values()
                    ->all(),
        ];
    }
}
