<?php
namespace App\Jobs;
use App\Optimization\OptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class GenerateCampaignOptimizations implements ShouldQueue {
 use Dispatchable,InteractsWithQueue,Queueable,SerializesModels;
 public int $tries=1;public int $timeout=240;
 public function __construct(public int $optimizationRunId){$this->onQueue(config('optimization.queue','optimization'));}
 public function handle(OptimizationService $service):void{$service->execute($this->optimizationRunId);}
}
