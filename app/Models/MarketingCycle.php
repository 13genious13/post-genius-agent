<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MarketingCycle extends Model {
 protected $fillable=['business_id','campaign_id','created_by','status','idempotency_key','stage','step_count','snapshot','last_error','started_at','completed_at'];
 protected function casts():array{return ['snapshot'=>'array','started_at'=>'datetime','completed_at'=>'datetime'];}
 public function business(){return $this->belongsTo(Business::class);}
 public function campaign(){return $this->belongsTo(Campaign::class);}
 public function steps(){return $this->hasMany(MarketingCycleStep::class);}
}
