<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tableau de Bord');
    }

    /** @test */
    public function non_admin_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function sectoral_admin_can_access_sectoral_dashboard()
    {
        $sectoralAdmin = User::factory()->create(['role' => 'sectoral_admin']);
        $this->actingAs($sectoralAdmin);

        $response = $this->get(route('sectoral.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_access_user_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get(route('user.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tableau de Bord');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_dashboards()
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('sectoral.dashboard'))->assertRedirect(route('login'));
        $this->get(route('user.dashboard'))->assertRedirect(route('login'));
    }
}














