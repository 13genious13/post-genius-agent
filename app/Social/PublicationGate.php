<?php
namespace App\Social;
use App\Models\SocialPublication;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
class PublicationGate {
 public function assertAllowed(SocialPublication $p):void {
  $p->loadMissing(['account','content','creative','business.agentControl']);$control=$p->business->agentControl;
  if(!$control || !$control->allow_publishing || !$control->allow_scheduling)throw new RuntimeException('Publishing or scheduling disabled in Agent Controls.');
  if($control->require_publish_approval && !$p->approved_at)throw new RuntimeException('Publication requires human approval.');
  if($p->content->business_id!==$p->business_id || $p->content->campaign_id!==$p->campaign_id || $p->content->status!=='approved')throw new RuntimeException('Content is not approved or does not belong to this campaign.');
  if($p->account->business_id!==$p->business_id || !$p->account->isUsable())throw new RuntimeException('Social account unavailable or belongs to another business.');
  if($p->account->platform!==$p->content->platform)throw new RuntimeException('Content platform differs from account platform.');
  $allowed=$control->allowed_platforms??[];if(!in_array($p->account->platform,$allowed,true))throw new RuntimeException('Platform is not permitted by Agent Controls.');
  if($p->creative_asset_id){
   if(!$p->creative || $p->creative->business_id!==$p->business_id || $p->creative->content_asset_id!==$p->content_asset_id || $p->creative->status!=='approved' || !$p->creative->storage_path)throw new RuntimeException('Creative is not approved or does not match content.');
   $latest=\App\Models\CreativeAsset::where('content_asset_id',$p->content_asset_id)->max('version');if($p->creative->version!==$latest)throw new RuntimeException('A newer creative version exists; re-review the latest creative.');
   if(!Storage::disk($p->creative->storage_disk)->exists($p->creative->storage_path))throw new RuntimeException('Creative media missing.');
  }
  if($p->account->platform==='instagram' && !$p->creative_asset_id)throw new RuntimeException('Instagram requires an approved image creative.');
 }
}
