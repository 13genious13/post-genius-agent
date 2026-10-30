<?php
namespace App\Analytics;
use App\Models\PublicationMetricSnapshot;
use Illuminate\Support\Facades\DB;
class PerformanceSummary {
 public function forBusiness(int $businessId,?int $campaignId=null):array {
  // Latest observation per publication only: never sum repeated cumulative snapshots.
  $latest=PublicationMetricSnapshot::query()->select('social_publication_id')->selectRaw('MAX(id) as latest_id')->where('business_id',$businessId)->groupBy('social_publication_id');
  $query=PublicationMetricSnapshot::query()->joinSub($latest,'latest',fn($join)=>$join->on('publication_metric_snapshots.id','=','latest.latest_id'))
   ->join('social_publications as p','p.id','=','publication_metric_snapshots.social_publication_id')->where('p.business_id',$businessId);
  if($campaignId)$query->where('p.campaign_id',$campaignId);
  $rows=$query->get(['publication_metric_snapshots.*','p.campaign_id','p.id as publication_id']);
  $totals=[];foreach(['reach','impressions','likes','comments','shares','saves','clicks','video_views','interactions'] as $metric){
   $observed=$rows->filter(fn($row)=>$row->{$metric}!==null);$totals[$metric]=['value'=>$observed->sum($metric),'sample_size'=>$observed->count()];
  }
  return ['publication_count'=>$rows->count(),'totals'=>$totals,'rows'=>$rows];
 }
}
