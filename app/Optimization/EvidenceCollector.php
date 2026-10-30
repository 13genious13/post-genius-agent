<?php
namespace App\Optimization;
use App\Models\PublicationMetricSnapshot;
use App\Models\MarketingMemory;
use Illuminate\Support\Facades\DB;
class EvidenceCollector {
 public function collect(int $businessId,int $campaignId):array {
  // Choose most recently collected snapshot per publication; do not add cumulative daily values.
  $latest=PublicationMetricSnapshot::query()->where('business_id',$businessId)->select('social_publication_id')->selectRaw('MAX(id) AS last_id')->groupBy('social_publication_id');
  $rows=PublicationMetricSnapshot::query()->joinSub($latest,'latest',fn($j)=>$j->on('publication_metric_snapshots.id','=','latest.last_id'))
   ->join('social_publications as p','p.id','=','publication_metric_snapshots.social_publication_id')
   ->where('p.business_id',$businessId)->where('p.campaign_id',$campaignId)->where('p.status','published')
   ->orderByDesc('p.published_at')->limit(max(3,min(200,config('optimization.max_posts',60))))
   ->get(['p.id as publication_id','p.published_at','p.caption_snapshot','publication_metric_snapshots.reach','publication_metric_snapshots.interactions','publication_metric_snapshots.impressions','publication_metric_snapshots.clicks','publication_metric_snapshots.likes','publication_metric_snapshots.comments','publication_metric_snapshots.shares','publication_metric_snapshots.saves','publication_metric_snapshots.platform','publication_metric_snapshots.collected_at']);
  $minReach=config('optimization.minimum_reach',20);
  $eligible=$rows->filter(fn($r)=>$r->reach!==null && (int)$r->reach >= $minReach && $r->interactions!==null)->values();
  $posts=$eligible->map(fn($r)=>[
   'publication_id'=>(int)$r->publication_id,'platform'=>(string)$r->platform,
   'published_at'=>(string)$r->published_at,'collected_at'=>(string)$r->collected_at,
   'reach'=>(int)$r->reach,'interactions'=>(int)$r->interactions,
   'interaction_rate'=>round((int)$r->interactions / max(1,(int)$r->reach),6),
   'impressions'=>$r->impressions===null?null:(int)$r->impressions,
   'clicks'=>$r->clicks===null?null:(int)$r->clicks,
   'caption_excerpt'=>mb_substr((string)$r->caption_snapshot,0,240),
  ])->all();
  $memories=MarketingMemory::query()->where('business_id',$businessId)->where('campaign_id',$campaignId)->where('status','active')
   ->latest('observed_at')->limit(8)->get(['id','title','observation','confidence','sample_size','evidence_publication_ids'])
   ->map(fn($m)=>['id'=>$m->id,'title'=>$m->title,'observation'=>$m->observation,'confidence'=>$m->confidence,'sample_size'=>$m->sample_size,'evidence_publication_ids'=>$m->evidence_publication_ids])->all();
  return ['posts'=>$posts,'memories'=>$memories,'total_snapshot_posts'=>$rows->count(),'eligible_posts'=>count($posts),'metric_definition'=>'interactions / reach; latest snapshot per publication; no causal inference'];
 }
}
