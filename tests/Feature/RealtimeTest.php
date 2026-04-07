<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\InvestmentSubmission;
use App\Events\NewSubmissionCreated;
use App\Events\SubmissionStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class RealtimeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_submission_created_event_is_fired()
    {
        Event::fake();

        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $submission = InvestmentSubmission::factory()->create([
            'user_id' => $user->id
        ]);

        event(new NewSubmissionCreated($submission, $user->id));

        Event::assertDispatched(NewSubmissionCreated::class, function ($event) use ($submission) {
            return $event->submission->id === $submission->id;
        });
    }

    /** @test */
    public function submission_status_updated_event_is_fired()
    {
        Event::fake();

        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $submission = InvestmentSubmission::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        event(new SubmissionStatusUpdated($submission, 'pending', 'approved', $user->id));

        Event::assertDispatched(SubmissionStatusUpdated::class, function ($event) use ($submission) {
            return $event->submission->id === $submission->id
                && $event->oldStatus === 'pending'
                && $event->newStatus === 'approved';
        });
    }

    /** @test */
    public function realtime_authentication_requires_auth()
    {
        $response = $this->postJson('/api/realtime/authenticate', [
            'channel_name' => 'private-user.1',
            'socket_id' => '123.456'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_get_realtime_stats()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->getJson('/api/realtime/stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'total_submissions',
                'pending_submissions',
                'approved_submissions',
                'rejected_submissions',
                'last_updated'
            ]
        ]);
    }
}














