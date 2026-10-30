<?php
namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\{ContentAsset,CreativeAsset,SocialPublication,SocialPublicationEvent};
use App\Social\PublicationGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovalScheduleController extends Controller
{
    private function businessIds(): array
    {
        return auth()->user()->businesses()->pluck('businesses.id')->all();
    }

    private function canEditPublication(SocialPublication $publication): void
    {
        abort_unless(in_array($publication->business_id, $this->businessIds()), 403);
        abort_unless($publication->content && $publication->content->business_id === $publication->business_id, 403);
        $this->authorize('update', $publication->content->campaign);
    }

    public function approvals(Request $request)
    {
        $ids = $this->businessIds();
        $businessId = $request->integer('business_id') ?: null;
        if ($businessId && !in_array($businessId, $ids)) abort(403);
        $scope = $businessId ? [$businessId] : $ids;
        $contents = ContentAsset::with('campaign')->whereIn('business_id', $scope)->where('status', 'draft')->latest()->limit(60)->get();
        $creatives = CreativeAsset::with('contentAsset')->whereIn('business_id', $scope)->whereIn('status', ['awaiting_approval','revision_required'])->latest()->limit(60)->get();
        $publications = SocialPublication::with(['content','account'])->whereIn('business_id', $scope)->where('status','draft')->latest()->limit(60)->get();
        $businesses = auth()->user()->businesses()->orderBy('name')->get(['businesses.id','businesses.name']);
        return view('workflow.approvals', compact('contents','creatives','publications','businesses','businessId'));
    }

    public function schedule(Request $request)
    {
        $ids = $this->businessIds();
        $businessId = $request->integer('business_id') ?: null;
        if ($businessId && !in_array($businessId, $ids)) abort(403);
        $scope = $businessId ? [$businessId] : $ids;
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->addMonth()->endOfMonth();
        if ($to->lt($from) || $from->diffInDays($to) > 93) throw ValidationException::withMessages(['to'=>'Select a date range of at most 93 days.']);
        $publications = SocialPublication::with(['account','content'])->whereIn('business_id', $scope)
            ->whereBetween('scheduled_at', [$from,$to])->orderBy('scheduled_at')->limit(500)->get();
        $businesses = auth()->user()->businesses()->orderBy('name')->get(['businesses.id','businesses.name']);
        return view('workflow.schedule', compact('publications','businesses','businessId','from','to'));
    }

    public function requestRevision(Request $request, ContentAsset $contentAsset)
    {
        abort_unless(in_array($contentAsset->business_id, $this->businessIds()),403);
        $this->authorize('update', $contentAsset->campaign);
        $data = $request->validate(['reason'=>['required','string','min:5','max:1500']]);
        DB::transaction(function () use ($contentAsset,$data) {
            $asset = ContentAsset::whereKey($contentAsset->id)->lockForUpdate()->firstOrFail();
            abort_unless($asset->status === 'draft',409,'Only drafts can be returned for revision.');
            $asset->update(['status'=>'rejected','reviewed_at'=>now(),'reviewed_by'=>auth()->id(),
                'metadata'=>array_merge($asset->metadata ?? [], ['revision_requested'=>true,'revision_reason'=>$data['reason']])]);
        });
        return back()->with('success','Revision requested. Create a new draft after updating the instructions.');
    }

    public function reschedule(Request $request, SocialPublication $publication, PublicationGate $gate)
    {
        $this->canEditPublication($publication);
        $data = $request->validate(['scheduled_at'=>['required','date','after:now']]);
        DB::transaction(function () use ($publication,$data,$gate) {
            $p = SocialPublication::whereKey($publication->id)->lockForUpdate()->firstOrFail();
            abort_unless(in_array($p->status,['draft','scheduled'],true),409,'Publication can no longer be rescheduled.');
            $old = $p->status;
            $prior = $p->scheduled_at?->toIso8601String();
            $p->scheduled_at = $data['scheduled_at'];
            if ($old === 'scheduled') {
                try { $gate->assertAllowed($p); } catch (\RuntimeException $e) { throw ValidationException::withMessages(['scheduled_at'=>$e->getMessage()]); }
            }
            $p->save();
            SocialPublicationEvent::create(['social_publication_id'=>$p->id,'actor_id'=>auth()->id(),
                'event'=>'rescheduled','from_status'=>$old,'to_status'=>$old,
                'data'=>['previous_scheduled_at'=>$prior,'scheduled_at'=>$p->scheduled_at->toIso8601String()]]);
        });
        return back()->with('success','Scheduled time updated.');
    }
}
