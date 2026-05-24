<?php

namespace App\Services;

use App\Events\TaskCreated;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Auth\Access\AuthorizationException;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function getUserTasks(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->taskRepository->getAllByUser($userId, $filters);
    }

    public function createTask(int $userId, array $data): Task
    {
        $data['user_id'] = $userId;
        $data['status']  = $data['status'] ?? 'todo';

        $task = $this->taskRepository->create($data);

        // Déclenche l'événement → notifie l'utilisateur (asynchrone)
        event(new TaskCreated($task));

        return $task;
    }

    public function updateTask(int $userId, int $taskId, array $data): Task
    {
        $task = $this->taskRepository->findById($taskId);

        if (!$task || $task->user_id !== $userId) {
            throw new AuthorizationException('Task not found or access denied.');
        }

        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask(int $userId, int $taskId): bool
    {
        $task = $this->taskRepository->findById($taskId);

        if (!$task || $task->user_id !== $userId) {
            throw new AuthorizationException('Task not found or access denied.');
        }

        return $this->taskRepository->delete($task);
    }

    public function getTask(int $userId, int $taskId): Task
    {
        $task = $this->taskRepository->findById($taskId);

        if (!$task || $task->user_id !== $userId) {
            throw new AuthorizationException('Task not found or access denied.');
        }

        return $task;
    }
}
