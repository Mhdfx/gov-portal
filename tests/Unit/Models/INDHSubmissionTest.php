<?php

namespace Tests\Unit\Models;

use App\Models\INDHSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class INDHSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function indh_submission_belongs_to_user()
    {
        $user = User::factory()->create();
        $submission = INDHSubmission::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $submission->user);
        $this->assertEquals($user->id, $submission->user->id);
    }

    /** @test */
    public function indh_submission_has_submission_number()
    {
        $submission = INDHSubmission::factory()->create();

        $this->assertNotNull($submission->submission_number);
        $this->assertStringStartsWith('INDH', $submission->submission_number);
    }

    /** @test */
    public function indh_submission_has_status()
    {
        $submission = INDHSubmission::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $submission->status);
    }
}














