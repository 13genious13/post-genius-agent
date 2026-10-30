<?php




namespace App\Jobs;




use App\AI\Agents\StrategyAgent;
use App\Models\CampaignStrategy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;




class GenerateCampaignStrategy implements ShouldQueue
{
    use Queueable;




    public int $tries = 2;




    public int $timeout = 300;




    public array $backoff = [
        30,
        90,
    ];




    public function __construct(
        public int $strategyId
    ) {
        $this->onQueue(
            'agents'
        );
    }




    public function handle(
        StrategyAgent $agent
    ): void {
        $strategy =
            CampaignStrategy::find(
                $this->strategyId
            );




        if (!$strategy) {
            \Illuminate\Support\Facades\Log::warning('Strategy job skipped: record missing', ['strategy_id' => $this->strategyId]);
            return;
        }

        if (
            $strategy->status
            === 'completed'
        ) {
            return;
        }




        $agent->run(
            $strategy
        );
    }




    public function failed(
        ?\Throwable $exception
    ): void {
        $strategy =
            CampaignStrategy::find(
                $this->strategyId
            );




        if (!$strategy || $strategy->status === 'completed') {
            return;
        }




        $strategy->update([
            'status' => 'failed',




            'error_message' =>
                mb_substr(
                    $exception?->getMessage()
                    ?? 'Strategy generation failed.',
                    0,
                    5000
                ),




            'completed_at' => now(),
        ]);
    }
}
