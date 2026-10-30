<?php
namespace App\Jobs;
use App\Analytics\AnalyticsSyncService;
use App\Models\AnalyticsSyncRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class SyncSocialAnalytics implements ShouldQueue {
 use Queueable;public int $tries=1;public int $timeout=240;
 public function __construct(public int $syncRunId){$this->onQueue('analytics');}
 public function handle(AnalyticsSyncService $service):void{$run=AnalyticsSyncRun::find($this->syncRunId);if($run)$service->run($run);}
 public function failed(?\Throwable $e):void{AnalyticsSyncRun::whereKey($this->syncRunId)->whereIn('status',['pending','running'])->update(['status'=>'failed','error_message'=>'Analytics sync failed. See application logs.','completed_at'=>now()]);}
}
