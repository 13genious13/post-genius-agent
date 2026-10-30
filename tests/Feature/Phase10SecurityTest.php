<?php
namespace Tests\Feature;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class Phase10SecurityTest extends TestCase {
 use RefreshDatabase;
 public function test_guest_cannot_open_analytics():void{$business=Business::factory()->create();$this->get(route('analytics.index',$business))->assertRedirect();}
 public function test_guest_cannot_trigger_sync():void{$business=Business::factory()->create();$this->post(route('analytics.sync',$business))->assertRedirect();}
 // Add authenticated cross-business 403 tests using the project's Phase 2 business membership factory and policy.
}
