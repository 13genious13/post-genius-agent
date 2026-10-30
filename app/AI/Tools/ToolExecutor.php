<?php




namespace App\AI\Tools;




use App\AI\Services\AgentActivityLogger;
use App\Models\AgentRun;
use App\Models\AgentTask;
use App\Models\AgentToolCall;
use Throwable;




class ToolExecutor
{
    public function __construct(
        protected ToolRegistry $registry,
        protected AgentActivityLogger $activity
    ) {
    }




    public function execute(
        AgentRun $run,
        string $toolKey,
        array $arguments = [],
        ?AgentTask $task = null,
        ?string $agentKey = null
    ): array {
        $tool = $this->registry->get(
            $toolKey
        );




        $call = AgentToolCall::create([
            'agent_run_id' =>
                $run->id,




            'agent_task_id' =>
                $task?->id,




            'agent_key' =>
                $agentKey,




            'tool_key' =>
                $toolKey,




            'status' =>
                'running',




            'arguments' =>
                $arguments,




            'started_at' =>
                now(),
        ]);




        $started = microtime(true);




        $this->activity->info(
            $run,
            'Tool started',
            $tool->name(),
            $agentKey,
            $task,
            [
                'tool' => $toolKey,
            ]
        );




        try {
            $result = $tool->execute(
                $run,
                $arguments
            );




            $latency = (int) round(
                (
                    microtime(true)
                    - $started
                ) * 1000
            );




            $call->update([
                'status' =>
                    'completed',




                'result' =>
                    $this->safeResult(
                        $result
                    ),




                'latency_ms' =>
                    $latency,




                'completed_at' =>
                    now(),
            ]);




            $this->activity->success(
                $run,
                'Tool completed',
                $tool->name(),
                $agentKey,
                $task,
                [
                    'tool' => $toolKey,
                    'latency_ms' => $latency,
                ]
            );




            return $result;




        } catch (Throwable $e) {
            $latency = (int) round(
                (
                    microtime(true)
                    - $started
                ) * 1000
            );




            $call->update([
                'status' =>
                    'failed',




                'error_message' =>
                    mb_substr(
                        $e->getMessage(),
                        0,
                        5000
                    ),




                'latency_ms' =>
                    $latency,




                'completed_at' =>
                    now(),
            ]);




            $this->activity->error(
                $run,
                'Tool failed',
                $tool->name(),
                $agentKey,
                $task,
                [
                    'tool' => $toolKey,
                ]
            );




            throw $e;
        }
    }




    protected function safeResult(
        array $result
    ): array {
        /*
        * Phase 6 tools only expose application data.
        * Never add API keys, Authorization headers,
        * environment variables or secrets here.
        */




        return $result;
    }
}
