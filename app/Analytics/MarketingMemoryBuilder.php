<?php
namespace App\Analytics;
use App\Models\MarketingMemory;
use App\Models\SocialPublication;
use Illuminate\Support\Facades\DB;
class MarketingMemoryBuilder {
 public function __construct(private PerformanceSummary $summary){}
 public function rebuild(int $businessId,int $campaignId):?MarketingMemory {
  $data=$this->summary->forBusiness($businessId,$campaignId);$rows=$data['rows'];$min=max(2,config('analytics.memory_min_sample'));
  // Compare only posts with actual reach and interaction metrics, avoiding missing=zero errors.
  $eligible=$rows->filter(fn($r)=>$r->reach!==null && $r->reach>0 && $r->interactions!==null);
  if($eligible->count()<$min)return null;
  $ranked=$eligible->map(fn($r)=>['publication_id'=>$r->publication_id,'rate'=>round($r->interactions/$r->reach,6)])->sortByDesc('rate')->values();
  $best=$ranked->first();$worst=$ranked->last();
  return MarketingMemory::updateOrCreate(['campaign_id'=>$campaignId,'memory_key'=>'observed_engagement_range'],[
   'business_id'=>$businessId,'category'=>'performance','title'=>'Observed engagement range',
   'observation'=>'Among '.$eligible->count().' posts with comparable reach and interaction metrics, observed interaction/reach ratios ranged from '.round($worst['rate']*100,2).'% to '.round($best['rate']*100,2).'%. This is descriptive, not a causal claim or a prediction.',
   'confidence'=>$eligible->count()>=10?'medium':'low','sample_size'=>$eligible->count(),
   'evidence_publication_ids'=>$ranked->pluck('publication_id')->all(),
   'metrics'=>['best_rate'=>$best['rate'],'worst_rate'=>$worst['rate'],'definition'=>'total_interactions / reach'],
   'status'=>'active','observed_at'=>now(),
  ]);
 }
}
