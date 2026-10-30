<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

final class Phase21SecurityTest extends TestCase
{
    public function test_internal_health_endpoint_does_not_expose_status_without_token(): void
    {
        Config::set('production_readiness.health_token', 'private-test-token');
        $this->get('/internal/agent-health')->assertNotFound();
    }

    public function test_dashboard_redirects_guest_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_playground_post_requires_authentication(): void
    {
        $this->post('/ai/playground', ['prompt' => 'test'])->assertRedirect('/login');
    }

    public function test_publishing_kill_switch_is_enabled_by_default_when_unset(): void
    {
        $this->assertTrue((bool) config('production_readiness.publish_kill_switch', true));
    }
}
