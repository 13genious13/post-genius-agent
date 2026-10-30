<?php

namespace App\Jobs;

use App\AI\Agents\ResearchAgent;
use App\Models\ResearchRun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunCampaignResearch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;

    public int $timeout = 300;

    public array $backoff = [30, 90];

    public bool $failOnTimeout = true;

    public function __construct(
        public int $researchRunId
    ) {
        $this->onQueue('research');
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping(
                'research-run-' . $this->researchRunId
            ))
                ->shared()
                ->releaseAfter(30)
                ->expireAfter(360),
        ];
    }

    public function handle(ResearchAgent $agent): void
    {
        $run = ResearchRun::find($this->researchRunId);

        // Research run was deleted or is no longer available.
        if (!$run) {
            Log::warning('Research job skipped: run not found', [
                'research_run_id' => $this->researchRunId,
                'job' => self::class,
            ]);

            return;
        }

        // Avoid processing already completed research.
        if ($run->status === 'completed') {
            Log::info('Research job skipped: already completed', [
                'research_run_id' => $run->id,
            ]);

            return;
        }

        Log::info('Research job started', [
            'research_run_id' => $run->id,
            'status' => $run->status,
        ]);

        try {
            $agent->run($run);

            $run->refresh();

            Log::info('Research job execution finished', [
                'research_run_id' => $run->id,
                'status' => $run->status,
            ]);

        } catch (\Throwable $e) {

            Log::error('Research job attempt failed', [
                'research_run_id' => $run->id,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            throw $e;
        }
    }

    public function failed(?Throwable $exception): void
    {
        $run = ResearchRun::find(
            $this->researchRunId
        );

        if (!$run || $run->status === 'completed') {
            return;
        }

        Log::error('Research job permanently failed', [
            'research_run_id' => $this->researchRunId,
            'error' => $exception?->getMessage(),
        ]);

        $run->update([
            'status' => 'failed',
            'error_message' => 'Research processing failed. Check application logs.',
            'completed_at' => now(),
        ]);
    }
}
