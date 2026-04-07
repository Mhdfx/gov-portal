<?php

namespace App\Listeners;

use App\Events\NewJobApplication;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendJobApplicationNotification implements ShouldQueue
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
    public function handle(NewJobApplication $event): void
    {
        $this->notificationService->sendJobApplicationNotification(
            $event->companyUserId,
            $event->candidateName,
            $event->jobTitle
        );
    }
}






























