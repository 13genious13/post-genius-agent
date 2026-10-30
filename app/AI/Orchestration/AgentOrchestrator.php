<?php




namespace App\AI\Orchestration;




use App\AI\Services\AgentActivityLogger;
use App\AI\Tools\ToolExecutor;
use App\Models\AgentControl;
use App\Models\AgentRun;
use Throwable;




class AgentOrchestrator
{
    public function __construct(
        protected ToolExecutor $tools,
        protected AgentTaskPlanner $planner,
        protected AgentActivityLogger $activity
    ) {
    }




    public function execute(
        AgentRun $run
    ): AgentRun {
        try {
            $run->update([
                'status' =>
                    'running',




                'current_agent' =>
                    'orchestrator',




                'current_stage' =>
                    'initializing',




                'progress' =>
                    5,




                'started_at' =>
                    $run->started_at
                        ?: now(),




                'error_message' =>
                    null,
            ]);




            $this->activity->info(
                $run,
                'Agent run started',
                $run->objective,
                'orchestrator'
            );




            $control =
                AgentControl::firstOrCreate(
                    [
                        'business_id' =>
                            $run->business_id,
                    ],
                    [
                        'autonomy_mode' =>
                            'assisted',
                    ]
                );




            /*
            * --------------------------------
            * Business context
            * --------------------------------
            */




            $run->update([
                'current_stage' =>
                    'business_context',




                'progress' =>
                    10,
            ]);




            $business =
                $this->tools->execute(
                    $run,
                    'get_business_profile',
                    [],
                    null,
                    'orchestrator'
                );




            /*
            * --------------------------------
            * Campaign context
            * --------------------------------
            */




            $run->update([
                'current_stage' =>
                    'campaign_context',




                'progress' =>
                    20,
            ]);




            $campaign =
                $this->tools->execute(
                    $run,
                    'get_campaign',
                    [],
                    null,
                    'orchestrator'
                );




            /*
            * --------------------------------
            * Research
            * --------------------------------
            */




            $run->update([
                'current_agent' =>
                    'research_agent',




                'current_stage' =>
                    'research_validation',




                'progress' =>
                    30,
            ]);




            $research =
                $this->tools->execute(
                    $run,
                    'get_research',
                    [],
                    null,
                    'research_agent'
                );




            $this->activity->success(
                $run,
                'Research accepted',
                'Completed research evidence is available to the orchestration run.',
                'research_agent',
                null,
                [
                    'research_run_id' =>
                        $run->research_run_id,
                ]
            );




            /*
            * --------------------------------
            * Strategy
            * --------------------------------
            */




            $run->update([
                'current_agent' =>
                    'strategy_agent',




                'current_stage' =>
                    'strategy_validation',




                'progress' =>
                    45,
            ]);




            $strategy =
                $this->tools->execute(
                    $run,
                    'get_strategy',
                    [],
                    null,
                    'strategy_agent'
                );




            $this->activity->success(
                $run,
                'Strategy accepted',
                'Completed strategy is ready for execution planning.',
                'strategy_agent'
            );




            /*
            * --------------------------------
            * Strategy Actions
            * --------------------------------
            */




            $run->update([
                'current_agent' =>
                    'orchestrator',




                'current_stage' =>
                    'action_discovery',




                'progress' =>
                    60,
            ]);




            $actions =
                $this->tools->execute(
                    $run,
                    'get_strategy_actions',
                    [],
                    null,
                    'orchestrator'
                );




            $context =
                new AgentRunContext(
                    business:
                    $business,




                    campaign:
                    $campaign,




                    research:
                    $research,




                    strategy:
                    $strategy,




                    actions:
                    $actions,
                );




            /*
            * Store a compact context.
            * Do not store external page bodies.
            */




            $run->update([
                'context' => [
                    'business_id' =>
                        $business['id'] ?? null,




                    'campaign_id' =>
                        $campaign['id'] ?? null,




                    'research_run_id' =>
                        $research['id'] ?? null,




                    'strategy_id' =>
                        $strategy['id'] ?? null,




                    'action_count' =>
                        count($actions),
                ],
            ]);




            /*
            * --------------------------------
            * Task planning
            * --------------------------------
            */




            $run->update([
                'current_stage' =>
                    'task_planning',




                'progress' =>
                    75,
            ]);




            $tasks =
                $this->planner->plan(
                    $run,
                    $context->actions,
                    $control
                );




            $this->activity->success(
                $run,
                'Execution plan created',
                $tasks->count()
                .' tasks were assigned to agent capabilities.',
                'orchestrator',
                null,
                [
                    'task_count' =>
                        $tasks->count(),
                ]
            );




            foreach ($tasks as $task) {
                $available =
                    (bool) data_get(
                        $task->metadata,
                        'capability_available',
                        false
                    );




                if ($available) {
                    $this->activity->info(
                        $run,
                        'Task ready',
                        $task->title,
                        $task->agent_key,
                        $task,
                        [
                            'status' =>
                                $task->status,
                        ]
                    );
                } else {
                    $this->activity->warning(
                        $run,
                        'Task waiting',
                        data_get(
                            $task->metadata,
                            'reason'
                        ),
                        $task->agent_key,
                        $task
                    );
                }
            }




            /*
            * Phase 6 ends at delegation.
            *
            * It does NOT pretend that future
            * agents have executed tasks.
            */




            $ready =
                $tasks
                    ->where(
                        'status',
                        'ready'
                    )
                    ->count();




            $waiting =
                $tasks
                    ->where(
                        'status',
                        'waiting_capability'
                    )
                    ->count();




            $blocked =
                $tasks
                    ->where(
                        'status',
                        'blocked'
                    )
                    ->count();




            $review =
                $tasks
                    ->where(
                        'status',
                        'needs_review'
                    )
                    ->count();




            $run->update([
                'status' =>
                    'completed',




                'current_agent' =>
                    null,




                'current_stage' =>
                    'delegation_complete',




                'progress' =>
                    100,




                'result' => [
                    'task_count' =>
                        $tasks->count(),




                    'ready' =>
                        $ready,




                    'waiting_capability' =>
                        $waiting,




                    'blocked' =>
                        $blocked,




                    'needs_review' =>
                        $review,
                ],




                'summary' => [
                    'message' =>
                        'Research and strategy were validated and strategy actions were delegated to agent capabilities.',




                    'next_step' =>
                        $waiting > 0
                            ? 'Install the required execution agents.'
                            : 'Review ready agent tasks.',
                ],




                'metrics' => [
                    'tools_used' =>
                        $run
                            ->toolCalls()
                            ->count(),




                    'tasks_created' =>
                        $tasks->count(),




                    'activities' =>
                        $run
                            ->activities()
                            ->count(),
                ],




                'completed_at' =>
                    now(),
            ]);




            $this->activity->success(
                $run,
                'Agent run completed',
                'Orchestration and delegation completed successfully.',
                'orchestrator'
            );




            return $run->fresh([
                'tasks',
                'activities',
                'toolCalls',
            ]);




        } catch (Throwable $e) {
            $run->update([
                'status' =>
                    'failed',




                'current_agent' =>
                    null,




                'current_stage' =>
                    'failed',




                'error_message' =>
                    mb_substr(
                        $e->getMessage(),
                        0,
                        5000
                    ),




                'completed_at' =>
                    now(),
            ]);




            $this->activity->error(
                $run,
                'Agent run failed',
                $e->getMessage(),
                'orchestrator'
            );




            throw $e;
        }
    }
}
