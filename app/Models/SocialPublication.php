<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SocialPublication extends Model {
 protected $fillable=['business_id','campaign_id','content_asset_id','creative_asset_id','social_account_id','created_by','approved_by','approved_at','idempotency_key','status','scheduled_at','claimed_at','published_at','attempts','external_post_id','permalink','caption_snapshot','media_disk','media_path','media_public_url','last_error','provider_result'];
 protected function casts():array{return ['approved_at'=>'datetime','scheduled_at'=>'datetime','claimed_at'=>'datetime','published_at'=>'datetime','provider_result'=>'array'];}
 public function business(){return $this->belongsTo(Business::class);}
 public function account(){return $this->belongsTo(SocialAccount::class,'social_account_id');}
 public function content(){return $this->belongsTo(ContentAsset::class,'content_asset_id');}
 public function creative(){return $this->belongsTo(CreativeAsset::class,'creative_asset_id');}
 public function events(){return $this->hasMany(SocialPublicationEvent::class);}
}
