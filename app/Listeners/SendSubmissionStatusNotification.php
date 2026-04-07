<?php

namespace App\Listeners;

use App\Events\SubmissionStatusChanged;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSubmissionStatusNotification implements ShouldQueue
{
    protected NotificationService $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(SubmissionStatusChanged $event): void
    {
        $this->notificationService->sendSubmissionStatusNotification(
            $event->userId,
            $event->submissionType,
            $event->status,
            $event->data
        );
    }
}






























