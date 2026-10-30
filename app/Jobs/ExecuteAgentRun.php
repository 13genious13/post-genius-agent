<?php




namespace App\Jobs;




use App\AI\Orchestration\AgentOrchestrator;
use App\Models\AgentRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;




class ExecuteAgentRun implements ShouldQueue
{
    use Queueable;




    public int $tries = 2;




    public int $timeout = 300;




    public array $backoff = [
        30,
        90,
    ];




    public function __construct(
        public int $agentRunId
    ) {
        $this->onQueue('agents');
    }




    public function handle(
        AgentOrchestrator $orchestrator
    ): void {
        $run = AgentRun::findOrFail(
            $this->agentRunId
        );




        if (
            $run->status ===
            'completed'
        ) {
            return;
        }




        $orchestrator->execute(
            $run
        );
    }




    public function failed(
        ?\Throwable $exception
    ): void {
        $run = AgentRun::find(
            $this->agentRunId
        );




        if (!$run) {
            return;
        }




        if (
            $run->status !==
            'completed'
        ) {
            $run->update([
                'status' =>
                    'failed',




                'error_message' =>
                    mb_substr(
                        $exception?->getMessage()
                        ?? 'Agent run failed.',
                        0,
                        5000
                    ),




                'completed_at' =>
                    now(),
            ]);
        }
    }
}
