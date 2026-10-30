<?php
namespace App\Analytics;
use App\Models\SocialPublication;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class MetaInsightsClient {
 public function fetch(SocialPublication $publication):array {
  $account=$publication->account;
  if(!$account || $account->status!=='active' || !$publication->external_post_id || !in_array($account->platform,['facebook','instagram'],true))throw new RuntimeException('Publication is not eligible for Meta insights.');
  $token=$account->access_token; // SocialAccount casts access_token encrypted in Phase 9.
  if(!$token)throw new RuntimeException('Social account token is missing.');
  $postId=$publication->external_post_id;
  if(!preg_match('/^[A-Za-z0-9_]+$/D',$postId))throw new RuntimeException('Invalid provider post identifier.');
  $metrics=$account->platform==='instagram'?config('analytics.instagram_media_metrics'):config('analytics.facebook_post_metrics');
  $result=[];$errors=[];
  // Query separately: a deprecated or unsupported metric must not discard other available metrics.
  foreach($metrics as $metric){
   if(!preg_match('/^[a-z][a-z0-9_]*$/D',$metric))continue;
   $response=Http::timeout(config('analytics.graph_timeout'))->acceptJson()->withToken($token)->get('https://graph.facebook.com/'.config('social.graph_version').'/'.$postId.'/insights',['metric'=>$metric]);
   if(!$response->successful()){$errors[$metric]=['http_status'=>$response->status(),'provider_code'=>data_get($response->json(),'error.code')];continue;}
   $items=$response->json('data')??[];
   foreach($items as $item){$name=$item['name']??$metric;$values=$item['values']??[];$value=$item['total_value']['value']??($values[0]['value']??null);
    if($value!==null)$result[$name]=$value;
   }
  }
  if(!$result)throw new RuntimeException('No supported insights returned; verify API permissions, metrics and post type.');
  return ['metrics'=>$result,'unavailable'=>$errors];
 }
}
