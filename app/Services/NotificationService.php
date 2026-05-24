<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;

class NotificationService
{
    public function notifyTaskCreated(Task $task): void
    {
        Notification::create([
            'user_id' => $task->user_id,
            'task_id' => $task->id,
            'type'    => 'task_created',
            'message' => "Tâche créée : \"{$task->title}\"",
        ]);
    }

    public function notifyTaskDueSoon(Task $task): void
    {
        Notification::create([
            'user_id' => $task->user_id,
            'task_id' => $task->id,
            'type'    => 'due_soon',
            'message' => "Rappel : la tâche \"{$task->title}\" est due dans moins de 24h.",
        ]);
    }

    public function getUserNotifications(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function markAsRead(int $userId, int $notifId): void
    {
        Notification::where('id', $notifId)
            ->where('user_id', $userId)
            ->update(['read_at' => now()]);
    }
}
