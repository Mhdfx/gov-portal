<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $submission;
    public $oldStatus;
    public $newStatus;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($submission, $oldStatus, $newStatus, $userId)
    {
        $this->submission = $submission;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        return 'submission.status.updated';
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_at' => now()->toIso8601String(),
            'message' => "Submission {$this->submission->submission_number} status changed from {$this->oldStatus} to {$this->newStatus}"
        ];
    }
}














