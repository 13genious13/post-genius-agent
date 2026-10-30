<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OptimizationExperiment extends Model {
 protected $fillable=['optimization_recommendation_id','business_id','campaign_id','status','hypothesis','test_plan','success_criteria','baseline_metrics','result_notes','created_by','started_at','ended_at'];
 protected function casts():array{return ['success_criteria'=>'array','baseline_metrics'=>'array','started_at'=>'datetime','ended_at'=>'datetime'];}
 public function recommendation(){return $this->belongsTo(OptimizationRecommendation::class,'optimization_recommendation_id');}
}
