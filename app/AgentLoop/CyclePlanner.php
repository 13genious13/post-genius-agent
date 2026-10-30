<?php
namespace App\AgentLoop;
use App\Models\{Campaign,MarketingCycle,MarketingCycleStep};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CyclePlanner {
 public function plan(MarketingCycle $cycle):void {
  DB::transaction(function()use($cycle){
   $cycle=MarketingCycle::whereKey($cycle->id)->lockForUpdate()->firstOrFail();
   if($cycle->status!=='draft')return;
   $campaign=Campaign::where('business_id',$cycle->business_id)->findOrFail($cycle->campaign_id);
   $research=$campaign->latestResearch; $strategy=$campaign->latestStrategy;
   if(!$research || $research->status!=='completed' || !$strategy || $strategy->status!=='completed' || (int)$strategy->research_run_id!==(int)$research->id)
    throw ValidationException::withMessages(['campaign'=>'Complete matching research and strategy before starting a cycle.']);
   $cycle->update(['status'=>'waiting_review','stage'=>'content_and_creative','started_at'=>now(),'snapshot'=>['research_run_id'=>$research->id,'campaign_strategy_id'=>$strategy->id]]);
   // Phase 7/8 assets must be generated and approved through their existing screens.
   // Never fabricate content or bypass approval by claiming that an agent ran.
   foreach(['content_review','creative_review','publication_review','analytics','optimization'] as $key){
    MarketingCycleStep::firstOrCreate(['marketing_cycle_id'=>$cycle->id,'step_key'=>$key],['status'=>'pending']);
   }
  });
 }
}
