<?php




namespace App\AI\Tools;




use App\AI\Tools\Contracts\AgentToolInterface;
use App\Models\AgentRun;
use RuntimeException;




class GetStrategyTool implements AgentToolInterface
{
    public function key(): string
    {
        return 'get_strategy';
    }




    public function name(): string
    {
        return 'Get Campaign Strategy';
    }




    public function description(): string
    {
        return 'Returns the completed campaign strategy selected for this run.';
    }




    public function execute(
        AgentRun $run,
        array $arguments = []
    ): array {
        $strategy = $run->strategy;




        if (!$strategy) {
            throw new RuntimeException(
                'No campaign strategy is attached.'
            );
        }




        if (
            $strategy->status !==
            'completed'
        ) {
            throw new RuntimeException(
                'Campaign strategy is not completed.'
            );
        }




        return [
            'id' => $strategy->id,
            'version' =>
                $strategy->version,
            'executive_summary' =>
                $strategy->executive_summary,
            'strategic_thesis' =>
                $strategy->strategic_thesis,
            'positioning' =>
                $strategy->positioning ?? [],
            'audience_strategy' =>
                $strategy->audience_strategy ?? [],
            'messaging_framework' =>
                $strategy->messaging_framework ?? [],
            'content_pillars' =>
                $strategy->content_pillars ?? [],
            'channel_strategy' =>
                $strategy->channel_strategy ?? [],
            'funnel_strategy' =>
                $strategy->funnel_strategy ?? [],
            'kpi_framework' =>
                $strategy->kpi_framework ?? [],
        ];
    }
}
