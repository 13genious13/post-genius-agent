<?php
namespace App\Http\Controllers;
use App\AgentGovernance\{ActionCatalog,AgentGovernanceService};
use App\Models\{AgentActionRequest,AgentGovernanceEvent,AgentGovernanceLimit,Business};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class AgentGovernanceController extends Controller {
 use AuthorizesRequests;
 public function index(Business $business,AgentGovernanceService $service){
  $this->authorize('view',$business);$limits=$service->limits($business);
  $requests=AgentActionRequest::where('business_id',$business->id)->with(['requester','reviewer'])->latest()->paginate(20);
  $events=AgentGovernanceEvent::where('business_id',$business->id)->latest()->limit(20)->get();
  $control=$business->agentControl;return view('agent-governance.index',compact('business','limits','requests','events','control'));
 }
 public function update(Request $request,Business $business,AgentGovernanceService $service){
  $this->authorize('update',$business);
  $data=$request->validate(['max_daily_requests'=>'required|integer|min:1|max:100','max_pending_approvals'=>'required|integer|min:1|max:100',
   'pause_all_agents'=>'sometimes|boolean','require_two_person_review'=>'sometimes|boolean','blocked_action_keys'=>'array',
   'blocked_action_keys.*'=>['string',Rule::in(array_keys(ActionCatalog::ACTIONS))]]);
  DB::transaction(function()use($service,$business,$request,$data){
   $limits=$service->limits($business);
   $limits->update(['max_daily_requests'=>$data['max_daily_requests'],'max_pending_approvals'=>$data['max_pending_approvals'],
    'pause_all_agents'=>$request->boolean('pause_all_agents'),'require_two_person_review'=>$request->boolean('require_two_person_review'),
    'blocked_action_keys'=>array_values(array_unique($data['blocked_action_keys']??[]))]);
   $service->event($business->id,null,$request->user()->id,'limits_updated',['paused'=>$limits->pause_all_agents,'blocked'=>$limits->blocked_action_keys]);
  });
  return back()->with('status','Governance limits saved.');
 }
 public function createRequest(Request $request,Business $business,AgentGovernanceService $service){
  $this->authorize('update',$business);
  $data=$request->validate(['action_key'=>['required',Rule::in(array_keys(ActionCatalog::ACTIONS))],
   'idempotency_key'=>'required|string|min:8|max:100','reason'=>'nullable|string|max:1000']);
  $item=$service->request($business,$data['action_key'],['reason'=>$data['reason']??null],$data['idempotency_key'],$request->user()->id);
  return redirect()->route('agent-governance.index',$business)->with('status','Action request #'.$item->id.' created. No action executed.');
 }
 public function decide(Request $request,Business $business,AgentActionRequest $actionRequest,AgentGovernanceService $service){
  $this->authorize('update',$business);abort_unless((int)$actionRequest->business_id===(int)$business->id,404);
  $data=$request->validate(['decision'=>'required|in:approved,rejected','note'=>'nullable|string|max:2000']);
  $service->decide($actionRequest,$data['decision'],$request->user()->id,$data['note']??null);
  return back()->with('status','Decision recorded. No action executed.');
 }
}
