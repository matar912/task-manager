<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

// ShouldQueue => ce listener est exécuté de manière ASYNCHRONE via la file
class SendTaskCreatedNotification implements ShouldQueue
{
    public string $queue = 'notifications';

    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(TaskCreated $event): void
    {
        $this->notificationService->notifyTaskCreated($event->task);
    }
}
