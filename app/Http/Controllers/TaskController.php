<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(name="Tasks", description="Gestion des tâches")
 */
class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    /**
     * @OA\Get(
     *   path="/api/tasks",
     *   summary="Liste des tâches de l'utilisateur connecté",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Liste paginée des tâches")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $tasks = $this->taskService->getUserTasks(
            auth()->id(),
            $request->only(['status', 'priority', 'category_id', 'search'])
        );

        return TaskResource::collection($tasks);
    }

    /**
     * @OA\Post(
     *   path="/api/tasks",
     *   summary="Créer une nouvelle tâche",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask(auth()->id(), $request->validated());
        return response()->json(new TaskResource($task), 201);
    }

    /**
     * @OA\Get(
     *   path="/api/tasks/{id}",
     *   summary="Détail d'une tâche",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTask(auth()->id(), $id);
        return response()->json(new TaskResource($task));
    }

    /**
     * @OA\Put(
     *   path="/api/tasks/{id}",
     *   summary="Modifier une tâche",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->updateTask(auth()->id(), $id, $request->validated());
        return response()->json(new TaskResource($task));
    }

    /**
     * @OA\Delete(
     *   path="/api/tasks/{id}",
     *   summary="Supprimer une tâche",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->taskService->deleteTask(auth()->id(), $id);
        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
