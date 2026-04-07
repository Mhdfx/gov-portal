<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerformanceMonitoringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function performance_metrics_requires_authentication()
    {
        $response = $this->getJson('/api/performance/metrics');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_get_performance_metrics()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->getJson('/api/performance/metrics');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'database',
                'cache',
                'memory',
                'requests',
                'timestamp'
            ]
        ]);
    }

    /** @test */
    public function performance_metrics_includes_database_info()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->getJson('/api/performance/metrics');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertArrayHasKey('database', $data);
        $this->assertArrayHasKey('status', $data['database']);
    }

    /** @test */
    public function performance_metrics_includes_memory_info()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->getJson('/api/performance/metrics');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertArrayHasKey('memory', $data);
        $this->assertArrayHasKey('memory_usage_mb', $data['memory']);
        $this->assertArrayHasKey('memory_peak_mb', $data['memory']);
    }

    /** @test */
    public function slow_queries_endpoint_requires_authentication()
    {
        $response = $this->getJson('/api/performance/slow-queries');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_get_slow_queries()
    {
        $user = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->getJson('/api/performance/slow-queries');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }
}














