<?php
namespace App\Production;
use App\Models\SocialPublication;
use Illuminate\Support\Facades\DB;
use RuntimeException;
final class PublishingSafety {
 public function assertCanPublish(SocialPublication $publication): void {
  if (!config('production_readiness.enabled', true)) throw new RuntimeException('Production safety is disabled.');
  if (config('production_readiness.publish_kill_switch', true)) throw new RuntimeException('Publishing emergency stop is active.');
  if (!config('agent_governance.enabled', false)) throw new RuntimeException('Agent governance must be enabled.');
  if (config('production_readiness.require_governance', true)) {
   $limit=DB::table('agent_governance_limits')->where('business_id',$publication->business_id)->first();
   if (!$limit || (bool)$limit->pause_all_agents) throw new RuntimeException('Business agent execution is paused or not configured.');
   $blocked=json_decode($limit->blocked_action_keys ?? '[]',true);
   if (!is_array($blocked) || in_array('publication.publish',$blocked,true)) throw new RuntimeException('Publishing action is blocked.');
  }
 }
}
