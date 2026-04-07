<?php

namespace App\Listeners;

use App\Events\CompanyStatusChanged;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCompanyStatusNotification implements ShouldQueue
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
    public function handle(CompanyStatusChanged $event): void
    {
        $this->notificationService->sendCompanyApprovalNotification(
            $event->userId,
            $event->status,
            $event->companyName
        );
    }
}






























