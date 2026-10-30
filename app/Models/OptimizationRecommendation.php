<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OptimizationRecommendation extends Model {
 protected $fillable=['optimization_run_id','business_id','campaign_id','recommendation_key','category','title','rationale','proposed_change','priority','status','evidence_publication_ids','supporting_metrics','confidence','reviewed_by','review_note','reviewed_at'];
 protected function casts():array{return ['evidence_publication_ids'=>'array','supporting_metrics'=>'array','reviewed_at'=>'datetime'];}
 public function run(){return $this->belongsTo(OptimizationRun::class,'optimization_run_id');}
 public function experiment(){return $this->hasOne(OptimizationExperiment::class);}
}
