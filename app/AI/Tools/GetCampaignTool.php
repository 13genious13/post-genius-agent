<?php




namespace App\AI\Tools;




use App\AI\Tools\Contracts\AgentToolInterface;
use App\Models\AgentRun;
use RuntimeException;




class GetCampaignTool implements AgentToolInterface
{
    public function key(): string
    {
        return 'get_campaign';
    }




    public function name(): string
    {
        return 'Get Campaign';
    }




    public function description(): string
    {
        return 'Returns the active campaign and marketing goal for the agent run.';
    }




    public function execute(
        AgentRun $run,
        array $arguments = []
    ): array {
        if (!$run->campaign_id) {
            throw new RuntimeException(
                'Agent run has no campaign.'
            );
        }




        $campaign = $run
            ->campaign()
            ->with([
                'marketingGoal',
                'latestBrief',
            ])
            ->firstOrFail();




        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'objective' =>
                $campaign->objective,
            'offer' =>
                $campaign->offer,
            'call_to_action' =>
                $campaign->call_to_action,
            'platforms' =>
                $campaign->platforms ?? [],
            'target_locations' =>
                $campaign->target_locations ?? [],
            'primary_kpis' =>
                $campaign->primary_kpis ?? [],




            'marketing_goal' =>
                $campaign->marketingGoal
                    ? [
                    'id' =>
                        $campaign
                            ->marketingGoal
                            ->id,




                    'name' =>
                        $campaign
                            ->marketingGoal
                            ->name,




                    'goal_type' =>
                        $campaign
                            ->marketingGoal
                            ->goal_type,




                    'primary_kpi' =>
                        $campaign
                            ->marketingGoal
                            ->primary_kpi,




                    'target_value' =>
                        $campaign
                            ->marketingGoal
                            ->target_value,
                ]
                    : null,




            'brief' =>
                $campaign->latestBrief
                    ? [
                    'id' =>
                        $campaign
                            ->latestBrief
                            ->id,




                    'summary' =>
                        $campaign
                            ->latestBrief
                            ->executive_summary,
                ]
                    : null,
        ];
    }
}
