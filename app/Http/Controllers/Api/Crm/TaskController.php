<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crm\TaskRequest;
use App\Http\Resources\Crm\TaskResource;
use App\Models\Crm\Task;
use App\Services\Crm\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Get a list of tasks with filtering options.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->all();
        $tasks = $this->taskService->getTasks($filters);
        
        return TaskResource::collection($tasks);
    }

    /**
     * Get a single task.
     *
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Create a new task.
     *
     * @param TaskRequest $request
     * @return TaskResource
     */
    public function store(TaskRequest $request): TaskResource
    {
        $task = $this->taskService->createTask($request->validated());
        
        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing task.
     *
     * @param TaskRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $task = $this->taskService->updateTask($task, $request->validated());
        
        return new TaskResource($task);
    }

    /**
     * Mark a task as completed.
     *
     * @param Task $task
     * @return TaskResource
     */
    public function complete(Task $task): TaskResource
    {
        $result = $task->complete();
        
        if (!$result) {
            return response()->json(['error' => 'Failed to complete task'], 500);
        }
        
        return new TaskResource($task->fresh());
    }

    /**
     * Delete a task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $result = $this->taskService->deleteTask($task);
        
        if (!$result) {
            return response()->json(['error' => 'Failed to delete task'], 500);
        }
        
        return response()->json(['message' => 'Task deleted successfully']);
    }
} 