<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CreativeAsset extends Model {
 protected $fillable=['business_id','campaign_id','content_asset_id','agent_task_id','created_by','version','status','platform','asset_type','design_spec','prompt','storage_disk','storage_path','mime_type','bytes','image_provider','image_model','brand_review','brand_score','review_notes','reviewed_by','reviewed_at','error_message'];
 protected function casts():array {return ['design_spec'=>'array','brand_review'=>'array','reviewed_at'=>'datetime'];}
 public function business(){return $this->belongsTo(Business::class);}
 public function campaign(){return $this->belongsTo(Campaign::class);}
 public function contentAsset(){return $this->belongsTo(ContentAsset::class);}
 public function approvals(){return $this->hasMany(CreativeApproval::class);}
 public function imageUrl():?string {return $this->storage_path ? route('creative-library.image',$this) : null;}

    public function socialPublications(){return $this->hasMany(\App\Models\SocialPublication::class);}
}
