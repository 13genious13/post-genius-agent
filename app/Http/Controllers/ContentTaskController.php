<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateContentTask;
use App\Models\AgentTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContentTaskController extends Controller
{
    public function generate(AgentTask $agentTask)
    {
        $agentTask->loadMissing('run.campaign');
        abort_unless($agentTask->run?->campaign,404);
        $this->authorize('update',$agentTask->run->campaign);
        if ($agentTask->agent_key !== 'content_agent') abort(422,'Not a content task.');
        $control = $agentTask->run->business->agentControl()->firstOrCreate([]);
        if (!$control->allow_content_generation) throw ValidationException::withMessages(['task'=>'Content generation disabled.']);
        $claimed = DB::table('agent_tasks')->where('id',$agentTask->id)->whereIn('status',['ready','failed'])->update(['status'=>'queued','updated_at'=>now()]);
        if (!$claimed) return back()->with('error','Task is not ready or is already running.');
        GenerateContentTask::dispatch($agentTask->id);
        return back()->with('success','Content generation queued.');
    }
}
