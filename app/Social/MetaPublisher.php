<?php
namespace App\Social;
use App\Models\SocialPublication;
use RuntimeException;
class MetaPublisher {
 public function __construct(private MetaGraph $graph){}
 public function publish(SocialPublication $p):array {
  $account=$p->account;$id=$account->external_id;$token=$account->access_token;
  if($account->platform==='facebook'){
   if($p->creative_asset_id){
    if(!$p->media_public_url)throw new RuntimeException('Facebook image requires a reachable public media URL.');
    $response=$this->graph->call('POST',$id.'/photos',$token,['url'=>$p->media_public_url,'caption'=>$p->caption_snapshot,'published'=>'true']);
   }else{$response=$this->graph->call('POST',$id.'/feed',$token,['message'=>$p->caption_snapshot]);}
   return ['external_post_id'=>(string)($response['post_id']??$response['id']??''),'provider_result'=>['id'=>$response['id']??null]];
  }
  if($account->platform==='instagram'){
   if(!$p->media_public_url)throw new RuntimeException('Instagram image requires a public HTTPS media URL.');
   $container=$this->graph->call('POST',$id.'/media',$token,['image_url'=>$p->media_public_url,'caption'=>$p->caption_snapshot]);
   $containerId=(string)($container['id']??'');if(!$containerId)throw new RuntimeException('Instagram did not return a container ID.');
   for($i=0;$i<(int)config('social.instagram_poll_attempts');$i++){
    $status=$this->graph->call('GET',$containerId,$token,['fields'=>'status_code']);
    if(($status['status_code']??'')==='FINISHED')break;
    if(($status['status_code']??'')==='ERROR')throw new RuntimeException('Instagram media processing failed.');
    sleep((int)config('social.instagram_poll_sleep'));
   }
   if(($status['status_code']??'')!=='FINISHED')throw new RuntimeException('Instagram media processing timed out; verify container status before retry.');
   $published=$this->graph->call('POST',$id.'/media_publish',$token,['creation_id'=>$containerId]);
   return ['external_post_id'=>(string)($published['id']??''),'provider_result'=>['creation_id'=>$containerId,'id'=>$published['id']??null]];
  }
  throw new RuntimeException('Unsupported platform.');
 }
}
