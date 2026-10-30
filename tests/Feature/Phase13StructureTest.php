<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\AgentLoop\ActionDispatcher;
use App\AgentLoop\CyclePlanner;
use App\AgentLoop\CycleAdvancer;
class Phase13StructureTest extends TestCase {
 public function test_cycle_services_resolve():void {$this->assertInstanceOf(CyclePlanner::class,app(CyclePlanner::class));$this->assertInstanceOf(CycleAdvancer::class,app(CycleAdvancer::class));$this->assertInstanceOf(ActionDispatcher::class,app(ActionDispatcher::class));}
 public function test_disabled_by_default():void {$this->assertFalse((bool)config('agent_loop.enabled'));}
}
