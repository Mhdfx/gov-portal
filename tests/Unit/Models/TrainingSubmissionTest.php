<?php

namespace Tests\Unit\Models;

use App\Models\TrainingSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function training_submission_belongs_to_user()
    {
        $user = User::factory()->create();
        $submission = TrainingSubmission::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $submission->user);
        $this->assertEquals($user->id, $submission->user->id);
    }

    /** @test */
    public function training_submission_has_submission_number()
    {
        $submission = TrainingSubmission::factory()->create();

        $this->assertNotNull($submission->submission_number);
        $this->assertStringStartsWith('TRN', $submission->submission_number);
    }

    /** @test */
    public function training_submission_has_status()
    {
        $submission = TrainingSubmission::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $submission->status);
    }
}














