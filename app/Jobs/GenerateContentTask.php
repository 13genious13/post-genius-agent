<?php

namespace App\Jobs;

use App\AI\Content\ContentAgent;
use App\AI\Services\AgentActivityLogger;
use App\Models\AgentTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class GenerateContentTask implements ShouldQueue
{
    use Queueable;
    public int $tries = 1;
    public int $timeout = 240;
    public function __construct(public int $taskId) { $this->onQueue('agents'); }
    public function handle(ContentAgent $agent, AgentActivityLogger $activity): void
    {
        $task = AgentTask::with('run')->findOrFail($this->taskId);
        if ($task->status !== 'queued' || $task->agent_key !== 'content_agent') return;
        $task->update(['status'=>'running','started_at'=>now(),'error_message'=>null]);
        $activity->info($task->run,'Content generation started',$task->title,'content_agent',$task);
        try {
            $asset = $agent->generate($task);
            $task->update(['status'=>'completed','completed_at'=>now(),'output'=>['content_asset_id'=>$asset->id,'version'=>$asset->version]]);
            $activity->success($task->run,'Content draft generated','Draft #'.$asset->id.' is ready for human review.','content_agent',$task);
        } catch (Throwable $e) {
            $task->update(['status'=>'failed','error_message'=>mb_substr($e->getMessage(),0,2000),'completed_at'=>now()]);
            $activity->error($task->run,'Content generation failed','Task failed. Review server logs.','content_agent',$task);
            report($e);
        }
    }
}
