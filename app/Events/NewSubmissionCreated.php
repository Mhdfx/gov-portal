<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSubmissionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $submission;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($submission, $userId)
    {
        $this->submission = $submission;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
            new PrivateChannel('admin.submissions'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'submission.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'submission_id' => $this->submission->id,
            'submission_type' => $this->submission->submission_type ?? 'unknown',
            'submission_number' => $this->submission->submission_number ?? null,
            'status' => $this->submission->status,
            'created_at' => $this->submission->created_at->toIso8601String(),
            'message' => "New submission {$this->submission->submission_number} created"
        ];
    }
}














