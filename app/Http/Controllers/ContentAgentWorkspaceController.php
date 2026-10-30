<?php

namespace App\Http\Controllers;

use App\Models\AgentRun;
use App\Models\AgentTask;
use App\Models\ContentAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContentAgentWorkspaceController extends Controller
{
    private function businessIds(): array
    {
        return auth()->user()->businesses()->pluck('businesses.id')->all();
    }

    public function index(Request $request)
    {
        $ids = $this->businessIds();
        $runs = AgentRun::with('campaign','business')
            ->whereIn('business_id', $ids)->whereNotNull('campaign_id')
            ->latest()->limit(100)->get();
        $tasks = AgentTask::with('run.campaign','run.business')
            ->where('agent_key', 'content_agent')
            ->whereHas('run', fn ($q) => $q->whereIn('business_id', $ids))
            ->latest()->paginate(12, ['*'], 'tasks_page');
        $assets = ContentAsset::with('campaign','business')
            ->whereIn('business_id', $ids)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()->paginate(12, ['*'], 'assets_page');
        return view('content-agent.index', compact('runs','tasks','assets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'agent_run_id' => ['required','integer'],
            'title' => ['required','string','max:180'],
            'description' => ['nullable','string','max:3000'],
            'platform' => ['required',Rule::in(['generic','facebook','instagram','linkedin','x','youtube','blog'])],
            'task_type' => ['required',Rule::in(['social_post','caption','blog','ad_copy','email'])],
            'instructions' => ['nullable','string','max:4000'],
        ]);
        $run = AgentRun::with('campaign','business')->whereIn('business_id', $this->businessIds())
            ->findOrFail($data['agent_run_id']);
        abort_unless($run->campaign && $run->campaign->business_id === $run->business_id, 422);
        $this->authorize('update', $run->campaign);
        $control = $run->business->agentControl()->firstOrCreate([], ['autonomy_mode'=>'assisted']);
        if (!$control->allow_content_generation) {
            throw ValidationException::withMessages(['agent_run_id'=>'Content generation is disabled for this business.']);
        }
        $allowed = $control->allowed_platforms;
        if (is_array($allowed) && $allowed && $data['platform'] !== 'generic' && !in_array($data['platform'], $allowed, true)) {
            throw ValidationException::withMessages(['platform'=>'This platform is not allowed by business agent controls.']);
        }
        $task = DB::transaction(fn () => $run->tasks()->create([
            'agent_key'=>'content_agent', 'task_type'=>$data['task_type'],
            'title'=>$data['title'], 'description'=>$data['description'] ?? '',
            'status'=>'ready', 'priority'=>2, 'requires_approval'=>true,
            'input'=>['platform'=>$data['platform'],'instructions'=>[$data['instructions'] ?? '']],
        ]));
        return redirect()->route('content-agent.index')->with('success', "Content task #{$task->id} created. Click Generate to queue it.");
    }

    public function update(Request $request, ContentAsset $contentAsset)
    {
        abort_unless(in_array($contentAsset->business_id, $this->businessIds()), 404);
        $this->authorize('update', $contentAsset->campaign);
        abort_unless($contentAsset->status === 'draft', 422, 'Only drafts can be edited.');
        $data = $request->validate([
            'title'=>['required','string','max:255'],
            'hook'=>['nullable','string','max:5000'],
            'caption'=>['required','string','max:30000'],
            'call_to_action'=>['nullable','string','max:2000'],
            'hashtags'=>['nullable','string','max:3000'],
        ]);
        $contentAsset->update([
            'title'=>$data['title'], 'hook'=>$data['hook'] ?? '',
            'caption'=>$data['caption'], 'call_to_action'=>$data['call_to_action'] ?? '',
            'hashtags'=>array_values(array_unique(array_filter(preg_split('/[\s,]+/u', $data['hashtags'] ?? '')))),
            'metadata'=>array_merge($contentAsset->metadata ?? [], ['human_edited_at'=>now()->toIso8601String(),'human_edited_by'=>auth()->id()]),
        ]);
        return redirect()->route('content-library.show', $contentAsset)->with('success','Draft saved. Approval is still required.');
    }
}
