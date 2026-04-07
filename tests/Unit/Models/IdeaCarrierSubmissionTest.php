<?php

namespace Tests\Unit\Models;

use App\Models\IdeaCarrierSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdeaCarrierSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function idea_carrier_submission_belongs_to_user()
    {
        $user = User::factory()->create();
        $submission = IdeaCarrierSubmission::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $submission->user);
        $this->assertEquals($user->id, $submission->user->id);
    }

    /** @test */
    public function idea_carrier_submission_has_submission_number()
    {
        $submission = IdeaCarrierSubmission::factory()->create();

        $this->assertNotNull($submission->submission_number);
        $this->assertStringStartsWith('IDEA', $submission->submission_number);
    }

    /** @test */
    public function idea_carrier_submission_has_status()
    {
        $submission = IdeaCarrierSubmission::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $submission->status);
    }
}














