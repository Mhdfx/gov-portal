<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function health_check_endpoint_returns_healthy_status()
    {
        $response = $this->getJson('/health');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'version',
            'environment'
        ]);
        
        $response->assertJson(['status' => 'healthy']);
    }

    /** @test */
    public function detailed_health_check_returns_all_services()
    {
        $response = $this->getJson('/health/detailed');

        // Accept both 200 (healthy) and 503 (degraded) as valid responses
        $this->assertContains($response->getStatusCode(), [200, 503]);
        $response->assertJsonStructure([
            'status',
            'checks' => [
                'application',
                'database',
                'cache',
                'storage',
                'redis'
            ],
            'timestamp',
            'version',
            'environment'
        ]);
    }

    /** @test */
    public function health_check_includes_database_status()
    {
        $response = $this->getJson('/health/detailed');

        $this->assertContains($response->getStatusCode(), [200, 503]);
        $data = $response->json('checks.database');
        
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('message', $data);
    }

    /** @test */
    public function health_check_includes_cache_status()
    {
        $response = $this->getJson('/health/detailed');

        $this->assertContains($response->getStatusCode(), [200, 503]);
        $data = $response->json('checks.cache');
        
        $this->assertArrayHasKey('status', $data);
    }

    /** @test */
    public function health_check_includes_storage_status()
    {
        $response = $this->getJson('/health/detailed');

        $this->assertContains($response->getStatusCode(), [200, 503]);
        $data = $response->json('checks.storage');
        
        $this->assertArrayHasKey('status', $data);
    }
}

