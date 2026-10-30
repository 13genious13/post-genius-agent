<?php
namespace App\AgentLoop;
use App\Models\{MarketingCycle,MarketingCycleStep,ContentAsset,CreativeAsset,SocialPublication,PublicationMetricSnapshot,OptimizationRun};
use Illuminate\Support\Facades\DB;
class CycleAdvancer {
 public function advance(MarketingCycle $cycle):MarketingCycle {
  return DB::transaction(function()use($cycle){
   $cycle=MarketingCycle::whereKey($cycle->id)->lockForUpdate()->firstOrFail();
   if(in_array($cycle->status,['completed','cancelled','failed'],true))return $cycle;
   $b=$cycle->business_id;$c=$cycle->campaign_id;
   $content=ContentAsset::where('business_id',$b)->where('campaign_id',$c)->where('status','approved')->exists();
   $creative=CreativeAsset::where('business_id',$b)->whereHas('contentAsset',fn($q)=>$q->where('campaign_id',$c))->where('status','approved')->exists();
   $publication=SocialPublication::where('business_id',$b)->where('campaign_id',$c)->where('status','published')->exists();
   $metrics=PublicationMetricSnapshot::where('business_id',$b)->whereHas('publication',fn($q)=>$q->where('campaign_id',$c)->where('status','published'))->exists();
   $optimization=OptimizationRun::where('business_id',$b)->where('campaign_id',$c)->where('status','completed')->exists();
   $states=['content_review'=>$content,'creative_review'=>$creative,'publication_review'=>$publication,'analytics'=>$metrics,'optimization'=>$optimization];
   $firstPending=null;
   foreach($states as $key=>$ok){
    $step=MarketingCycleStep::where('marketing_cycle_id',$cycle->id)->where('step_key',$key)->firstOrFail();
    if($ok && $step->status!=='completed')$step->update(['status'=>'completed','finished_at'=>now()]);
    if(!$ok && !$firstPending)$firstPending=$key;
   }
   $cycle->update(['stage'=>$firstPending??'completed','status'=>$firstPending?'waiting_review':'completed','step_count'=>count(array_filter($states)),'completed_at'=>$firstPending?null:now()]);
   return $cycle->refresh();
  });
 }
}
