<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PublicationMetricSnapshot extends Model {
 protected $fillable=['business_id','social_publication_id','analytics_sync_run_id','snapshot_date','platform','reach','impressions','likes','comments','shares','saves','clicks','video_views','interactions','available_metrics','provider_metadata','collected_at'];
 protected function casts():array{return ['snapshot_date'=>'date','collected_at'=>'datetime','available_metrics'=>'array','provider_metadata'=>'array'];}
 public function publication(){return $this->belongsTo(SocialPublication::class,'social_publication_id');}
}
