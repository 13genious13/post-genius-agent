<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AgentGovernanceLimit extends Model {
 protected $fillable=['business_id','max_daily_requests','max_pending_approvals','pause_all_agents','require_two_person_review','blocked_action_keys'];
 protected function casts():array{return ['pause_all_agents'=>'boolean','require_two_person_review'=>'boolean','blocked_action_keys'=>'array'];}
}
