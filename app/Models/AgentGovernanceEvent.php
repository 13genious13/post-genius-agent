<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AgentGovernanceEvent extends Model {
 protected $fillable=['business_id','agent_action_request_id','actor_id','event_key','details'];
 protected function casts():array{return ['details'=>'array'];}
 public function request(){return $this->belongsTo(AgentActionRequest::class,'agent_action_request_id');}
}
