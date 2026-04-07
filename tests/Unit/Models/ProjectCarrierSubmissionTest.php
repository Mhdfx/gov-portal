<?php

namespace Tests\Unit\Models;

use App\Models\ProjectCarrierSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCarrierSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function project_carrier_submission_belongs_to_user()
    {
        $user = User::factory()->create();
        $submission = ProjectCarrierSubmission::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $submission->user);
        $this->assertEquals($user->id, $submission->user->id);
    }

    /** @test */
    public function project_carrier_submission_has_submission_number()
    {
        $submission = ProjectCarrierSubmission::factory()->create();

        $this->assertNotNull($submission->submission_number);
        $this->assertStringStartsWith('PRJ', $submission->submission_number);
    }

    /** @test */
    public function project_carrier_submission_has_status()
    {
        $submission = ProjectCarrierSubmission::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $submission->status);
    }

    /** @test */
    public function project_carrier_submission_can_be_scoped_by_status()
    {
        ProjectCarrierSubmission::factory()->create(['status' => 'pending']);
        ProjectCarrierSubmission::factory()->create(['status' => 'approved']);
        ProjectCarrierSubmission::factory()->create(['status' => 'rejected']);

        $pending = ProjectCarrierSubmission::where('status', 'pending')->count();
        $this->assertEquals(1, $pending);
    }
}














