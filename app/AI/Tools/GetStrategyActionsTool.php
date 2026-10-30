<?php




namespace App\AI\Tools;




use App\AI\Tools\Contracts\AgentToolInterface;
use App\Models\AgentRun;
use RuntimeException;




class GetStrategyActionsTool implements AgentToolInterface
{
    public function key(): string
    {
        return 'get_strategy_actions';
    }




    public function name(): string
    {
        return 'Get Strategy Actions';
    }




    public function description(): string
    {
        return 'Returns planned executable actions from the campaign strategy.';
    }




    public function execute(
        AgentRun $run,
        array $arguments = []
    ): array {
        $strategy = $run->strategy;




        if (!$strategy) {
            throw new RuntimeException(
                'No campaign strategy attached.'
            );
        }




        $actions = $strategy
            ->actions()
            ->where('status', 'planned')
            ->orderBy('sort_order')
            ->get();




        return $actions
            ->map(fn ($action) => [
                'id' => $action->id,
                'action_type' =>
                    $action->action_type,
                'title' =>
                    $action->title,
                'description' =>
                    $action->description,
                'platform' =>
                    $action->platform,
                'funnel_stage' =>
                    $action->funnel_stage,
                'priority' =>
                    $action->priority,
                'instructions' =>
                    $action->instructions ?? [],
                'success_metrics' =>
                    $action->success_metrics ?? [],
            ])
            ->values()
            ->all();
    }
}
