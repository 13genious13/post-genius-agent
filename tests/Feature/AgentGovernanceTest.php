<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\AgentGovernance\ActionCatalog;
use App\Models\AgentGovernanceLimit;
class AgentGovernanceTest extends TestCase {
 use RefreshDatabase;
 public function test_action_catalog_is_explicit_allowlist():void {
  $this->assertArrayHasKey('publication.publish',ActionCatalog::ACTIONS);
  $this->expectException(\InvalidArgumentException::class);
  ActionCatalog::get('arbitrary.shell');
 }
 public function test_limits_cast_flags():void {
  $limit=new AgentGovernanceLimit(['pause_all_agents'=>1,'blocked_action_keys'=>['publication.publish']]);
  $this->assertTrue($limit->pause_all_agents);
  $this->assertSame(['publication.publish'],$limit->blocked_action_keys);
 }
}
