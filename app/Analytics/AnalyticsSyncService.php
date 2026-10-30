<?php
namespace App\Analytics;
use App\Models\AnalyticsSyncRun;
use App\Models\PublicationMetricSnapshot;
use App\Models\SocialPublication;
use Illuminate\Support\Facades\Log;
use Throwable;
class AnalyticsSyncService {
 public function __construct(private MetaInsightsClient $client,private MetricNormalizer $normalizer){}
 public function run(AnalyticsSyncRun $run):void {
  if($run->status!=='pending')return;
  $run->update(['status'=>'running','started_at'=>now()]);
  $query=SocialPublication::query()->with('account')->where('business_id',$run->business_id)->where('status','published')->whereNotNull('external_post_id')
   ->where('published_at','>=',now()->subDays(config('analytics.lookback_days')));
  if($run->social_account_id)$query->where('social_account_id',$run->social_account_id);
  $publications=$query->orderBy('id')->limit(config('analytics.sync_limit'))->get();
  $succeeded=0;$failed=0;
  foreach($publications as $publication){
   try {
    if(!$publication->account || $publication->account->business_id!==$run->business_id)throw new \RuntimeException('Account scope mismatch.');
    $response=$this->client->fetch($publication);
    $data=$this->normalizer->normalize($publication->account->platform,$response['metrics']);
    PublicationMetricSnapshot::updateOrCreate(
     ['social_publication_id'=>$publication->id,'snapshot_date'=>now()->toDateString()],
     array_merge($data,['business_id'=>$run->business_id,'analytics_sync_run_id'=>$run->id,'platform'=>$publication->account->platform,
      'available_metrics'=>array_keys($response['metrics']),'provider_metadata'=>['unavailable'=>$response['unavailable']], 'collected_at'=>now()])
    );$succeeded++;
   } catch(Throwable $e){$failed++;Log::warning('Analytics post sync failed',['sync_run_id'=>$run->id,'publication_id'=>$publication->id,'error_type'=>get_class($e)]);}
  }
  $run->update(['status'=>$failed>0?($succeeded>0?'partial':'failed'):'completed','posts_scanned'=>$publications->count(),'posts_succeeded'=>$succeeded,'posts_failed'=>$failed,'completed_at'=>now()]);
 }
}
