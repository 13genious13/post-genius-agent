<?php
namespace Tests\Feature;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
class InternalHealthTest extends TestCase {
 public function test_health_route_is_not_public():void {
  Config::set('production_readiness.health_token','test-secret');
  $this->get('/internal/agent-health')->assertNotFound();
 }
}
