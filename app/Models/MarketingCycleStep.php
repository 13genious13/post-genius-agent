<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MarketingCycleStep extends Model {
 protected $fillable=['marketing_cycle_id','step_key','action_key','status','agent_action_request_id','input','output','error_message','started_at','finished_at'];
 protected function casts():array{return ['input'=>'array','output'=>'array','started_at'=>'datetime','finished_at'=>'datetime'];}
 public function cycle(){return $this->belongsTo(MarketingCycle::class,'marketing_cycle_id');}
 public function actionRequest(){return $this->belongsTo(AgentActionRequest::class,'agent_action_request_id');}
}
