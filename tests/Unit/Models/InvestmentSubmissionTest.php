<?php

namespace Tests\Unit\Models;

use App\Models\InvestmentSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function investment_submission_belongs_to_user()
    {
        $user = User::factory()->create();
        $submission = InvestmentSubmission::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $submission->user);
        $this->assertEquals($user->id, $submission->user->id);
    }

    /** @test */
    public function investment_submission_has_submission_number()
    {
        $submission = InvestmentSubmission::factory()->create();

        $this->assertNotNull($submission->submission_number);
        $this->assertStringStartsWith('INV', $submission->submission_number);
    }

    /** @test */
    public function investment_submission_has_status()
    {
        $submission = InvestmentSubmission::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $submission->status);
    }

    /** @test */
    public function investment_submission_can_be_scoped_by_status()
    {
        InvestmentSubmission::factory()->create(['status' => 'pending']);
        InvestmentSubmission::factory()->create(['status' => 'approved']);
        InvestmentSubmission::factory()->create(['status' => 'rejected']);

        $pending = InvestmentSubmission::where('status', 'pending')->count();
        $this->assertEquals(1, $pending);
    }
}














