<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AgentActionRequest extends Model {
 protected $fillable=['business_id','campaign_id','agent_run_id','agent_task_id','requested_by','decided_by','action_key','status','idempotency_key','payload','policy_snapshot','reason','decision_note','expires_at','decided_at'];
 protected function casts():array{return ['payload'=>'array','policy_snapshot'=>'array','expires_at'=>'datetime','decided_at'=>'datetime'];}
 public function business(){return $this->belongsTo(Business::class);}
 public function campaign(){return $this->belongsTo(Campaign::class);}
 public function task(){return $this->belongsTo(AgentTask::class,'agent_task_id');}
 public function requester(){return $this->belongsTo(User::class,'requested_by');}
 public function reviewer(){return $this->belongsTo(User::class,'decided_by');}
}
