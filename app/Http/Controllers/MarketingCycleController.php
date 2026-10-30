<?php
namespace App\Http\Controllers;
use App\AgentLoop\{CyclePlanner,CycleAdvancer,ActionDispatcher};
use App\AgentGovernance\AgentGovernanceService;
use App\Models\{Business,Campaign,MarketingCycle,AgentActionRequest};
use App\Social\PublicationGate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class MarketingCycleController extends Controller {
 use AuthorizesRequests;
 private function scoped(Business $b,Campaign $c):void {abort_unless((int)$b->id===(int)$c->business_id,404);}
 private function cycleScoped(Business $b,Campaign $c,MarketingCycle $cycle):void {$this->scoped($b,$c);abort_unless((int)$cycle->business_id===(int)$b->id && (int)$cycle->campaign_id===(int)$c->id,404);}
 public function index(Business $business,Campaign $campaign){$this->authorize('view',$business);$this->scoped($business,$campaign);$cycles=MarketingCycle::where('business_id',$business->id)->where('campaign_id',$campaign->id)->latest()->paginate(15);return view('agent-loop.index',compact('business','campaign','cycles'));}
 public function store(Request $request,Business $business,Campaign $campaign,CyclePlanner $planner){$this->authorize('update',$business);$this->scoped($business,$campaign);abort_unless(config('agent_loop.enabled'),403);
  $data=$request->validate(['idempotency_key'=>'required|uuid']);
  $cycle=DB::transaction(function()use($business,$campaign,$data,$request){
   Campaign::whereKey($campaign->id)->lockForUpdate()->firstOrFail();
   $active=MarketingCycle::where('business_id',$business->id)->where('campaign_id',$campaign->id)->whereIn('status',['draft','waiting_review'])->first();if($active)return $active;
   $today=MarketingCycle::where('business_id',$business->id)->where('created_at','>=',now()->startOfDay())->count();
   if($today>=config('agent_loop.max_cycles_per_day'))throw ValidationException::withMessages(['cycle'=>'Daily cycle limit reached.']);
   return MarketingCycle::firstOrCreate(['business_id'=>$business->id,'idempotency_key'=>$data['idempotency_key']],['campaign_id'=>$campaign->id,'created_by'=>$request->user()->id,'status'=>'draft']);
  });
  $planner->plan($cycle);return redirect()->route('agent-loop.show',[$business,$campaign,$cycle]);
 }
 public function show(Business $business,Campaign $campaign,MarketingCycle $cycle){$this->authorize('view',$business);$this->cycleScoped($business,$campaign,$cycle);$cycle->load('steps.actionRequest');return view('agent-loop.show',compact('business','campaign','cycle'));}
 public function refresh(Business $business,Campaign $campaign,MarketingCycle $cycle,CycleAdvancer $advancer){$this->authorize('update',$business);$this->cycleScoped($business,$campaign,$cycle);$advancer->advance($cycle);return back()->with('status','Cycle status refreshed.');}
 public function requestAction(Request $request,Business $business,Campaign $campaign,MarketingCycle $cycle,AgentGovernanceService $governance){$this->authorize('update',$business);$this->cycleScoped($business,$campaign,$cycle);
  $data=$request->validate(['action_key'=>'required|in:optimization.analyze,publication.schedule','publication_id'=>'nullable|integer','idempotency_key'=>'required|uuid']);
  $payload=$data['action_key']==='publication.schedule'?['publication_id'=>$data['publication_id']??null]:[];
  $governance->request($business,$data['action_key'],$payload,$data['idempotency_key'],$request->user()->id,$campaign->id);
  return back()->with('status','Action submitted to governance approval inbox.');
 }
 public function execute(Business $business,Campaign $campaign,AgentActionRequest $actionRequest,ActionDispatcher $dispatcher,AgentGovernanceService $governance,PublicationGate $gate){$this->authorize('update',$business);$this->scoped($business,$campaign);
  abort_unless((int)$actionRequest->business_id===(int)$business->id && (int)$actionRequest->campaign_id===(int)$campaign->id,404);
  $dispatcher->dispatch($actionRequest->id,$governance,$gate);return back()->with('status','Approved action handed to its execution handler.');
 }
}
