<?php

namespace Tests\Unit\Models;

use App\Models\AutoEntrepreneurSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoEntrepreneurSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function auto_entrepreneur_submission_belongs_to_user()
    {
        $user = User::factory()->create();
        $submission = AutoEntrepreneurSubmission::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $submission->user);
        $this->assertEquals($user->id, $submission->user->id);
    }

    /** @test */
    public function auto_entrepreneur_submission_has_submission_number()
    {
        $submission = AutoEntrepreneurSubmission::factory()->create();

        $this->assertNotNull($submission->submission_number);
        $this->assertStringStartsWith('AUT-', $submission->submission_number);
    }

    /** @test */
    public function auto_entrepreneur_submission_has_status()
    {
        $submission = AutoEntrepreneurSubmission::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $submission->status);
    }
}














