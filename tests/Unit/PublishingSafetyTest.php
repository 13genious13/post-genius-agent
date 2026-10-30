<?php
namespace Tests\Unit;
use App\Models\SocialPublication;
use App\Production\PublishingSafety;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Tests\TestCase;
class PublishingSafetyTest extends TestCase {
 public function test_emergency_kill_switch_blocks_publishing():void {
  Config::set('production_readiness.enabled',true);
  Config::set('production_readiness.publish_kill_switch',true);
  $this->expectException(RuntimeException::class);
  app(PublishingSafety::class)->assertCanPublish(new SocialPublication(['business_id'=>1]));
 }
 public function test_governance_disabled_blocks_publishing():void {
  Config::set('production_readiness.enabled',true);
  Config::set('production_readiness.publish_kill_switch',false);
  Config::set('agent_governance.enabled',false);
  $this->expectException(RuntimeException::class);
  app(PublishingSafety::class)->assertCanPublish(new SocialPublication(['business_id'=>1]));
 }
}
