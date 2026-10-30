<?php
namespace App\AgentGovernance;
use App\Models\{AgentActionRequest,AgentControl,AgentGovernanceEvent,AgentGovernanceLimit,Business};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class AgentGovernanceService {
 public function limits(Business $business):AgentGovernanceLimit {
  return AgentGovernanceLimit::firstOrCreate(['business_id'=>$business->id]);
 }
 public function evaluate(Business $business,string $actionKey,?int $actorId=null):array {
  $definition=ActionCatalog::get($actionKey);
  $limits=$this->limits($business);
  $control=AgentControl::where('business_id',$business->id)->first();
  $reasons=[];
  if(!config('agent_governance.enabled'))$reasons[]='Governance is disabled.';
  if($limits->pause_all_agents)$reasons[]='Agents are paused.';
  if(in_array($actionKey,$limits->blocked_action_keys??[],true))$reasons[]='Action is blocked.';
  if(!$control)$reasons[]='Agent controls are missing.';
  if($control){
   $flag=match($definition['capability']){
    'content'=>(bool)$control->allow_content_generation,
    'creative'=>(bool)$control->allow_creative_generation,
    'research'=>(bool)$control->allow_research,
    'optimization'=>true,
    'scheduling'=>(bool)$control->allow_scheduling,
    'publishing'=>(bool)$control->allow_publishing,
   };
   if(!$flag)$reasons[]='Capability is disabled in Agent Controls.';
  }
  // High-risk actions always need explicit approval, regardless of autonomy mode.
  $requiresApproval=$definition['risk']==='high' || !$control || $control->autonomy_mode!=='autonomous';
  return ['allowed'=>count($reasons)===0,'reasons'=>$reasons,'requires_approval'=>$requiresApproval,
   'risk'=>$definition['risk'],'autonomy_mode'=>$control?->autonomy_mode??'manual','action_key'=>$actionKey];
 }
 public function request(Business $business,string $actionKey,array $payload,string $idempotencyKey,int $actorId,?int $campaignId=null,?int $runId=null,?int $taskId=null):AgentActionRequest {
  return DB::transaction(function()use($business,$actionKey,$payload,$idempotencyKey,$actorId,$campaignId,$runId,$taskId){
   Business::query()->whereKey($business->id)->lockForUpdate()->firstOrFail();
   $existing=AgentActionRequest::where('business_id',$business->id)->where('idempotency_key',$idempotencyKey)->first();
   if($existing)return $existing;
   $policy=$this->evaluate($business,$actionKey,$actorId);
   if(!$policy['allowed'])throw ValidationException::withMessages(['action'=>implode(' ',$policy['reasons'])]);
   $limits=$this->limits($business);
   $pending=AgentActionRequest::where('business_id',$business->id)->where('status','pending')->count();
   if($pending >= min($limits->max_pending_approvals,(int)config('agent_governance.max_pending_per_business',100)))throw ValidationException::withMessages(['action'=>'Approval queue limit reached.']);
   $today=AgentActionRequest::where('business_id',$business->id)->where('created_at','>=',now()->startOfDay())->count();
   if($today >= min($limits->max_daily_requests,(int)config('agent_governance.max_requests_per_day',100)))throw ValidationException::withMessages(['action'=>'Daily action request limit reached.']);
   $request=AgentActionRequest::create(['business_id'=>$business->id,'campaign_id'=>$campaignId,'agent_run_id'=>$runId,'agent_task_id'=>$taskId,'requested_by'=>$actorId,'action_key'=>$actionKey,'status'=>'pending',
    'idempotency_key'=>$idempotencyKey,'payload'=>$payload,'policy_snapshot'=>$policy,'expires_at'=>now()->addHours(max(1,(int)config('agent_governance.approval_ttl_hours',72)))]);
   $this->event($business->id,$request->id,$actorId,'requested',['action_key'=>$actionKey]);
   return $request;
  });
 }
 public function decide(AgentActionRequest $request,string $decision,int $actorId,?string $note):AgentActionRequest {
  return DB::transaction(function()use($request,$decision,$actorId,$note){
   $item=AgentActionRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
   if($item->status!=='pending')throw ValidationException::withMessages(['decision'=>'Already decided.']);
   if($item->expires_at?->isPast()){
    throw ValidationException::withMessages(['decision'=>'Request expired.']);
   }
   $business=Business::findOrFail($item->business_id);
   $policy=$this->evaluate($business,$item->action_key,$actorId);
   if($decision==='approved' && !$policy['allowed'])throw ValidationException::withMessages(['decision'=>'Action is no longer allowed.']);
   $limits=$this->limits($business);
   if($decision==='approved' && $limits->require_two_person_review && (int)$item->requested_by===$actorId)
    throw ValidationException::withMessages(['decision'=>'Another authorized user must approve this request.']);
   $item->update(['status'=>$decision,'decided_by'=>$actorId,'decided_at'=>now(),'decision_note'=>$note]);
   $this->event($item->business_id,$item->id,$actorId,$decision,['note_present'=>filled($note)]);
   return $item;
  });
 }
 public function event(int $businessId,?int $requestId,?int $actorId,string $eventKey,array $details=[]):void {
  AgentGovernanceEvent::create(['business_id'=>$businessId,'agent_action_request_id'=>$requestId,'actor_id'=>$actorId,'event_key'=>$eventKey,'details'=>$details]);
 }
 // Approved is permission to hand off, NOT proof of execution. Phase 13 must consume atomically.
 public function assertApprovedForExecution(AgentActionRequest $request):void {
  if($request->status!=='approved')throw ValidationException::withMessages(['action'=>'Approval is required.']);
  $policy=$this->evaluate(Business::findOrFail($request->business_id),$request->action_key);
  if(!$policy['allowed'])throw ValidationException::withMessages(['action'=>'Action is now disabled.']);
 }
}
