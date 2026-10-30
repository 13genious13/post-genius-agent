<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase20AccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_phase20_pages(): void
    {
        $this->get('/performance')->assertRedirect('/login');
        $this->get('/admin/system-settings')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_system_settings(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/admin/system-settings')->assertForbidden();
        $this->actingAs($user)->put('/admin/system-settings', [
            'company_label' => 'Changed',
            'default_timezone' => 'UTC',
        ])->assertForbidden();
    }

    public function test_admin_can_save_allowlisted_preferences(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->put('/admin/system-settings', [
            'company_label' => 'PostGenius',
            'support_email' => 'support@example.com',
            'default_timezone' => 'Asia/Kolkata',
            'maintenance_notice' => '',
            'admin_digest_enabled' => '1',
        ])->assertRedirect(route('admin.system-settings.index'));
        $this->assertDatabaseHas('system_settings', [
            'key' => 'general.company_label',
            'value' => 'PostGenius',
        ]);
    }
}
