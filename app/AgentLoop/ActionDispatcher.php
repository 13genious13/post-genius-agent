<?php
namespace App\AgentLoop;
use App\AgentGovernance\AgentGovernanceService;
use App\Models\{AgentActionRequest,AgentActionExecution,OptimizationRun,SocialPublication,AnalyticsSyncRun,SocialAccount};
use App\Jobs\{GenerateCampaignOptimizations,SyncSocialAnalytics};
use App\Social\PublicationGate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class ActionDispatcher {
 // Deliberately small allowlist. Content/creative/research require existing manual UI integration.
 private const HANDLERS=['optimization.analyze','publication.schedule'];
 public function dispatch(int $requestId,AgentGovernanceService $governance,PublicationGate $gate):AgentActionExecution {
  $result=DB::transaction(function()use($requestId,$governance,$gate){
   $request=AgentActionRequest::whereKey($requestId)->lockForUpdate()->firstOrFail();
   if(!in_array($request->action_key,self::HANDLERS,true))throw ValidationException::withMessages(['action'=>'No executable Phase 13 handler for this action.']);
   $existing=AgentActionExecution::where('agent_action_request_id',$request->id)->first();if($existing)return [$existing,null];
   $governance->assertApprovedForExecution($request);
   if($request->expires_at?->isPast())throw ValidationException::withMessages(['action'=>'Approval expired.']);
   $payload=$request->payload??[];
   if($request->action_key==='publication.schedule'){
    $id=filter_var($payload['publication_id']??null,FILTER_VALIDATE_INT);
    if(!$id)throw ValidationException::withMessages(['publication'=>'Valid publication_id required.']);
    $p=SocialPublication::where('business_id',$request->business_id)->where('campaign_id',$request->campaign_id)->whereKey($id)->lockForUpdate()->firstOrFail();
    if($p->status!=='draft')throw ValidationException::withMessages(['publication'=>'Only draft publications may be scheduled.']);
    $gate->assertAllowed($p);
    if(!$p->scheduled_at || !$p->scheduled_at->isFuture())throw ValidationException::withMessages(['publication'=>'Schedule must be in the future.']);
    $p->update(['status'=>'scheduled']);
    \App\Models\SocialPublicationEvent::create(['social_publication_id'=>$p->id,'event'=>'scheduled','from_status'=>'draft','to_status'=>'scheduled','message'=>'Scheduled through approved agent action.']);
    $execution=AgentActionExecution::create(['agent_action_request_id'=>$request->id,'status'=>'completed','handler'=>$request->action_key,'result'=>['publication_id'=>$p->id],'finished_at'=>now()]);
    return [$execution,null];
   }
   if(!$request->campaign_id)throw ValidationException::withMessages(['campaign'=>'Campaign required.']);
   $active=OptimizationRun::where('business_id',$request->business_id)->where('campaign_id',$request->campaign_id)->whereIn('status',['pending','running'])->first();
   $run=$active??OptimizationRun::create(['business_id'=>$request->business_id,'campaign_id'=>$request->campaign_id,'requested_by'=>$request->requested_by,'status'=>'pending','idempotency_key'=>(string)Str::uuid()]);
   $execution=AgentActionExecution::create(['agent_action_request_id'=>$request->id,'status'=>'completed','handler'=>$request->action_key,'result'=>['optimization_run_id'=>$run->id],'finished_at'=>now()]);
   return [$execution,$active?null:$run->id];
  });
  if($result[1])GenerateCampaignOptimizations::dispatch($result[1])->afterCommit();
  return $result[0];
 }
}
