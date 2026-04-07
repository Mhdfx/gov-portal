<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Constants\AppConstants;

class BulkOperationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function bulk_update_status_requires_authentication()
    {
        $response = $this->postJson('/api/v1/bulk/update-status', [
            'submission_ids' => [1, 2, 3],
            'status' => 'approved',
            'type' => 'investment'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function regular_user_cannot_perform_bulk_operations()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/bulk/update-status', [
            'submission_ids' => [1, 2, 3],
            'status' => 'approved',
            'type' => 'investment'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_perform_bulk_status_update()
    {
        $admin = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $submission1 = InvestmentSubmission::factory()->create(['status' => 'pending']);
        $submission2 = InvestmentSubmission::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->postJson('/api/v1/bulk/update-status', [
            'submission_ids' => [$submission1->id, $submission2->id],
            'status' => AppConstants::STATUS_APPROVED,
            'type' => 'investment'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertEquals(AppConstants::STATUS_APPROVED, $submission1->fresh()->status);
        $this->assertEquals(AppConstants::STATUS_APPROVED, $submission2->fresh()->status);
    }

    /** @test */
    public function bulk_delete_requires_main_admin()
    {
        $admin = User::factory()->create([
            'role' => 'institutional_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($admin)->postJson('/api/v1/bulk/delete', [
            'submission_ids' => [1, 2, 3],
            'type' => 'investment'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function main_admin_can_perform_bulk_delete()
    {
        $admin = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $submission1 = InvestmentSubmission::factory()->create();
        $submission2 = InvestmentSubmission::factory()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/bulk/delete', [
            'submission_ids' => [$submission1->id, $submission2->id],
            'type' => 'investment'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseMissing('investment_submissions', ['id' => $submission1->id]);
        $this->assertDatabaseMissing('investment_submissions', ['id' => $submission2->id]);
    }

    /** @test */
    public function bulk_operations_validate_input()
    {
        $admin = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($admin)->postJson('/api/v1/bulk/update-status', [
            'submission_ids' => [],
            'status' => 'invalid_status',
            'type' => 'invalid_type'
        ]);

        $response->assertStatus(422);
    }
}














