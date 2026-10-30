<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OptimizationRun extends Model {
 protected $fillable=['business_id','campaign_id','requested_by','campaign_strategy_id','status','idempotency_key','analysis_window','eligible_posts','evidence_snapshot','summary','error_message','started_at','completed_at'];
 protected function casts():array{return ['evidence_snapshot'=>'array','summary'=>'array','started_at'=>'datetime','completed_at'=>'datetime'];}
 public function campaign(){return $this->belongsTo(Campaign::class);}
 public function business(){return $this->belongsTo(Business::class);}
 public function recommendations(){return $this->hasMany(OptimizationRecommendation::class);}
}
