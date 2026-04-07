<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function analytics_dashboard_requires_authentication()
    {
        $response = $this->get('/analytics');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_analytics_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->get('/analytics');

        $response->assertStatus(200);
        $response->assertSee('Analytics Dashboard');
    }

    /** @test */
    public function analytics_api_returns_dashboard_data()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        // Create some submissions using factories
        InvestmentSubmission::factory()->count(5)->create(['status' => 'pending']);
        InvestmentSubmission::factory()->count(3)->create(['status' => 'approved']);
        ProjectCarrierSubmission::factory()->count(2)->create(['status' => 'rejected']);

        $response = $this->actingAs($user)->getJson('/api/v1/analytics/dashboard?period=30');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'overview',
                'submissions',
                'users',
                'trends',
                'top_sectors',
                'status_distribution'
            ]
        ]);
    }

    /** @test */
    public function analytics_api_returns_submissions_by_type()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        InvestmentSubmission::factory()->count(3)->create(['user_id' => $user->id]);
        ProjectCarrierSubmission::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/v1/analytics/submissions-by-type?period=30');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /** @test */
    public function analytics_api_returns_user_stats()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        InvestmentSubmission::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/v1/analytics/user-stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /** @test */
    public function analytics_api_returns_trends()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/analytics/trends?period=30');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /** @test */
    public function analytics_api_returns_top_sectors()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        AutoEntrepreneurSubmission::factory()->count(5)->create([
            'sector' => 'Technology'
        ]);
        AutoEntrepreneurSubmission::factory()->count(3)->create([
            'sector' => 'Agriculture'
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/analytics/top-sectors?period=30');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }
}

