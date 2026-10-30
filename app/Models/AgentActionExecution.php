<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AgentActionExecution extends Model {
 protected $fillable=['agent_action_request_id','marketing_cycle_id','status','handler','result','error_message','claimed_at','finished_at'];
 protected function casts():array{return ['result'=>'array','claimed_at'=>'datetime','finished_at'=>'datetime'];}
 public function request(){return $this->belongsTo(AgentActionRequest::class,'agent_action_request_id');}
}
