<?php

declare(strict_types=1);

namespace App\Services\Crm;

use App\Models\Crm\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * Get tasks with filtering and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getTasks(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Task::query()->with(['user', 'assignedTo', 'company', 'contact', 'opportunity']);

        // Apply filters
        if (isset($filters['company_id']) && $filters['company_id']) {
            $query->where('company_id', $filters['company_id']);
        }

        if (isset($filters['contact_id']) && $filters['contact_id']) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['opportunity_id']) && $filters['opportunity_id']) {
            $query->where('opportunity_id', $filters['opportunity_id']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority']) && $filters['priority']) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['assigned_to']) && $filters['assigned_to']) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by due date range
        if (isset($filters['due_date_from']) && $filters['due_date_from']) {
            $query->where('due_date', '>=', $filters['due_date_from']);
        }

        if (isset($filters['due_date_to']) && $filters['due_date_to']) {
            $query->where('due_date', '<=', $filters['due_date_to']);
        }

        // Filter by completion status
        if (isset($filters['completed']) && $filters['completed'] === 'true') {
            $query->completed();
        } elseif (isset($filters['completed']) && $filters['completed'] === 'false') {
            $query->incomplete();
        }

        // Filter by overdue status
        if (isset($filters['overdue']) && $filters['overdue'] === 'true') {
            $query->overdue();
        }

        // Filter by due today
        if (isset($filters['due_today']) && $filters['due_today'] === 'true') {
            $query->dueToday();
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        $data['user_id'] = auth()->id();
        
        if (!isset($data['status'])) {
            $data['status'] = Task::STATUS_NOT_STARTED;
        }
        
        return Task::create($data);
    }

    /**
     * Update an existing task.
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    /**
     * Delete a task.
     *
     * @param Task $task
     * @return bool
     */
    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Get task by ID with relationships.
     *
     * @param int $id
     * @return Task|null
     */
    public function getTaskById(int $id): ?Task
    {
        return Task::with(['user', 'assignedTo', 'company', 'contact', 'opportunity'])->find($id);
    }
} 