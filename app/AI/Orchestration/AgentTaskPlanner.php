<?php




namespace App\AI\Orchestration;




use App\Models\AgentControl;
use App\Models\AgentRun;
use App\Models\AgentTask;
use Illuminate\Support\Collection;




class AgentTaskPlanner
{
    public function plan(
        AgentRun $run,
        array $actions,
        AgentControl $control
    ): Collection {
        $run->tasks()->delete();




        $limit = max(
            1,
            min(
                $control->max_actions_per_run,
                100
            )
        );




        $actions = array_slice(
            $actions,
            0,
            $limit
        );




        $tasks = collect();




        foreach (
            $actions as $index => $action
        ) {
            if (!is_array($action)) {
                continue;
            }




            $assignment =
                $this->assignmentFor(
                    $action,
                    $control
                );




            $task = AgentTask::create([
                'agent_run_id' =>
                    $run->id,




                'strategy_action_id' =>
                    $action['id'] ?? null,




                'agent_key' =>
                    $assignment['agent'],




                'task_type' =>
                    $action[
                    'action_type'
                    ] ?? 'unknown',




                'title' =>
                    mb_substr(
                        $action['title']
                        ?? 'Untitled task',
                        0,
                        255
                    ),




                'description' =>
                    $action[
                    'description'
                    ] ?? null,




                'status' =>
                    $assignment['status'],




                'priority' =>
                    $this->priority(
                        $action['priority']
                        ?? null
                    ),




                'requires_approval' =>
                    $assignment[
                    'requires_approval'
                    ],




                'sort_order' =>
                    $index + 1,




                'input' => [
                    'platform' =>
                        $action[
                        'platform'
                        ] ?? null,




                    'funnel_stage' =>
                        $action[
                        'funnel_stage'
                        ] ?? null,




                    'instructions' =>
                        $action[
                        'instructions'
                        ] ?? [],




                    'success_metrics' =>
                        $action[
                        'success_metrics'
                        ] ?? [],
                ],




                'metadata' => [
                    'capability_available' =>
                        $assignment[
                        'available'
                        ],




                    'reason' =>
                        $assignment[
                        'reason'
                        ],
                ],
            ]);




            $tasks->push($task);
}




        return $tasks;
    }




    protected function assignmentFor(
        array $action,
        AgentControl $control
    ): array {
        $type =
            $action['action_type']
            ?? 'unknown';




        return match ($type) {
            'research' => [
                'agent' => 'research_agent',
                'available' => $control->allow_research,
                'status' => $control->allow_research ? 'ready' : 'blocked',
                'requires_approval' => false,
                'reason' => $control->allow_research ? 'Research capability is available.' : 'Research is disabled by Agent Control.',
            ],

            'content',
            'social',
            'landing_page' => [
                'agent' => 'content_agent',
                /*
                * Content Agent arrives in
                * the next phase.
                */
                'available' => false,
                'status' => 'waiting_capability',
                'requires_approval' => $control->require_content_approval,
                'reason' =>
                    'Content Agent is not installed yet.',
            ],
            'copywriting',
            'post',
            'carousel',
            'video_script',
            'email' => [
                'agent' => 'content_agent',
                'available' => (bool) $control->allow_content_generation,
                'status' => $control->allow_content_generation ? 'ready' : 'blocked',
                'requires_approval' => true,
                'reason' => $control->allow_content_generation ? 'Content Agent available for manual execution.' : 'Content generation disabled.',
            ],
            'creative' => [
                'agent' => 'creative_agent',
                'available' => false,
                'status' => 'waiting_capability',
                'requires_approval' => $control->require_creative_approval,
                'reason' =>
                    'Creative Agent is not installed yet.',
            ],




            'analytics',
            'optimization' => [
                'agent' =>
                    $type === 'analytics'
                        ? 'analytics_agent'
                        : 'optimization_agent',




                'available' => false,




                'status' =>
                    'waiting_capability',




                'requires_approval' =>
                    false,




                'reason' =>
                    ucfirst($type)
                    .' capability will be added in a later phase.',
            ],




            default => [
                'agent' =>
                    'human',




                'available' =>
                    false,




                'status' =>
                    'needs_review',




                'requires_approval' =>
                    true,




                'reason' =>
                    'No registered agent owns this action type.',
            ],
        };
    }




    protected function priority(
        ?string $priority
    ): string {
        return in_array(
            $priority,
            [
                'high',
                'medium',
                'low',
            ],
            true
        )
            ? $priority
            : 'medium';
    }
}
