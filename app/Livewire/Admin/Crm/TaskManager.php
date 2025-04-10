<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TaskManager extends Component
{
    use WithPagination;

    /**
     * Search term.
     *
     * @var string
     */
    public string $search = '';

    /**
     * Status filter.
     *
     * @var string
     */
    public string $status = '';

    /**
     * Priority filter.
     *
     * @var string
     */
    public string $priority = '';

    /**
     * Date range filter.
     *
     * @var string
     */
    public string $dateRange = '';

    /**
     * Company filter.
     *
     * @var int|null
     */
    public ?int $companyId = null;

    /**
     * Contact filter.
     *
     * @var int|null
     */
    public ?int $contactId = null;

    /**
     * Opportunity filter.
     *
     * @var int|null
     */
    public ?int $opportunityId = null;

    /**
     * User filter.
     *
     * @var int|null
     */
    public ?int $assignedTo = null;

    /**
     * Sort field.
     *
     * @var string
     */
    public string $sortField = 'due_date';

    /**
     * Sort direction.
     *
     * @var string
     */
    public string $sortDirection = 'asc';

    /**
     * Task being deleted.
     *
     * @var Task|null
     */
    public ?Task $deletingTask = null;

    /**
     * Task being edited or created.
     *
     * @var array
     */
    public array $taskForm = [
        'id' => null,
        'title' => '',
        'description' => '',
        'due_date' => '',
        'status' => '',
        'priority' => '',
        'company_id' => '',
        'contact_id' => '',
        'opportunity_id' => '',
        'assigned_to' => '',
    ];

    /**
     * Form mode.
     *
     * @var string
     */
    public string $formMode = 'create';

    /**
     * Show form modal.
     *
     * @var bool
     */
    public bool $showFormModal = false;

    /**
     * Task being viewed.
     *
     * @var Task|null
     */
    public ?Task $viewingTask = null;

    /**
     * Show task details modal.
     *
     * @var bool
     */
    public bool $showDetailsModal = false;

    /**
     * Reset pagination when filters change.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status changes.
     */
    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when priority changes.
     */
    public function updatedPriority(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when date range changes.
     */
    public function updatedDateRange(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when company filter changes.
     */
    public function updatedCompanyId(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when contact filter changes.
     */
    public function updatedContactId(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when opportunity filter changes.
     */
    public function updatedOpportunityId(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when user filter changes.
     */
    public function updatedAssignedTo(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by the given field.
     *
     * @param string $field
     * @return void
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Create a new task.
     *
     * @return void
     */
    public function createTask(): void
    {
        $this->resetValidation();
        $this->formMode = 'create';
        $this->taskForm = [
            'id' => null,
            'title' => '',
            'description' => '',
            'due_date' => now()->format('Y-m-d H:i'),
            'status' => Task::STATUS_NOT_STARTED,
            'priority' => Task::PRIORITY_MEDIUM,
            'company_id' => '',
            'contact_id' => '',
            'opportunity_id' => '',
            'assigned_to' => auth()->id(),
        ];
        $this->showFormModal = true;
    }

    /**
     * Edit an existing task.
     *
     * @param Task $task
     * @return void
     */
    public function editTask(Task $task): void
    {
        $this->resetValidation();
        $this->formMode = 'edit';
        $this->taskForm = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'due_date' => $task->due_date ? $task->due_date->format('Y-m-d H:i') : '',
            'status' => $task->status,
            'priority' => $task->priority,
            'company_id' => $task->company_id,
            'contact_id' => $task->contact_id,
            'opportunity_id' => $task->opportunity_id,
            'assigned_to' => $task->assigned_to,
        ];
        $this->showFormModal = true;
    }

    /**
     * Save the task.
     *
     * @return void
     */
    public function saveTask(): void
    {
        $this->validate([
            'taskForm.title' => 'required|string|max:255',
            'taskForm.description' => 'nullable|string',
            'taskForm.due_date' => 'nullable|date',
            'taskForm.status' => 'required|string|max:50',
            'taskForm.priority' => 'required|string|max:50',
            'taskForm.company_id' => 'nullable|exists:crm_companies,id',
            'taskForm.contact_id' => 'nullable|exists:crm_contacts,id',
            'taskForm.opportunity_id' => 'nullable|exists:crm_opportunities,id',
            'taskForm.assigned_to' => 'nullable|exists:users,id',
        ]);

        $data = [
            'title' => $this->taskForm['title'],
            'description' => $this->taskForm['description'],
            'due_date' => $this->taskForm['due_date'] ? date('Y-m-d H:i:s', strtotime($this->taskForm['due_date'])) : null,
            'status' => $this->taskForm['status'],
            'priority' => $this->taskForm['priority'],
            'company_id' => $this->taskForm['company_id'] ?: null,
            'contact_id' => $this->taskForm['contact_id'] ?: null,
            'opportunity_id' => $this->taskForm['opportunity_id'] ?: null,
            'assigned_to' => $this->taskForm['assigned_to'] ?: null,
        ];

        if ($this->formMode === 'edit') {
            $task = Task::findOrFail($this->taskForm['id']);
            $task->update($data);
            
            if ($task->status === Task::STATUS_COMPLETED && !$task->completed_at) {
                $task->update(['completed_at' => now()]);
            } elseif ($task->status !== Task::STATUS_COMPLETED) {
                $task->update(['completed_at' => null]);
            }
            
            session()->flash('success', 'Task updated successfully.');
        } else {
            $data['user_id'] = auth()->id();
            
            if ($data['status'] === Task::STATUS_COMPLETED) {
                $data['completed_at'] = now();
            }
            
            Task::create($data);
            session()->flash('success', 'Task created successfully.');
        }

        $this->showFormModal = false;
    }

    /**
     * Set the task to be deleted.
     *
     * @param Task $task
     * @return void
     */
    public function confirmTaskDeletion(Task $task): void
    {
        $this->deletingTask = $task;
    }

    /**
     * Delete the task.
     *
     * @return void
     */
    public function deleteTask(): void
    {
        if ($this->deletingTask) {
            $this->deletingTask->delete();
            $this->deletingTask = null;
            session()->flash('success', 'Task deleted successfully.');
        }
    }

    /**
     * Cancel task deletion.
     *
     * @return void
     */
    public function cancelDeletion(): void
    {
        $this->deletingTask = null;
    }

    /**
     * View task details.
     *
     * @param Task $task
     * @return void
     */
    public function viewTask(Task $task): void
    {
        $this->viewingTask = $task;
        $this->showDetailsModal = true;
    }

    /**
     * Close task details view.
     *
     * @return void
     */
    public function closeTaskView(): void
    {
        $this->viewingTask = null;
        $this->showDetailsModal = false;
    }

    /**
     * Toggle task completion status.
     *
     * @param Task $task
     * @return void
     */
    public function toggleTaskCompletion(Task $task): void
    {
        if ($task->status === Task::STATUS_COMPLETED) {
            $task->markAsInProgress();
            session()->flash('success', 'Task marked as in progress.');
        } else {
            $task->markAsCompleted();
            session()->flash('success', 'Task marked as completed.');
        }
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        $query = Task::query()
            ->with(['company', 'contact', 'opportunity', 'assignee']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // Apply status filter
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        // Apply priority filter
        if (!empty($this->priority)) {
            $query->where('priority', $this->priority);
        }

        // Apply date range filter
        if (!empty($this->dateRange)) {
            if ($this->dateRange === 'today') {
                $query->dueToday();
            } elseif ($this->dateRange === 'overdue') {
                $query->overdue();
            } elseif ($this->dateRange === 'upcoming') {
                $query->where('due_date', '>=', now())
                      ->where('due_date', '<=', now()->addDays(7));
            } elseif ($this->dateRange === 'next-30-days') {
                $query->where('due_date', '>=', now())
                      ->where('due_date', '<=', now()->addDays(30));
            }
        }

        // Apply company filter
        if ($this->companyId) {
            $query->where('company_id', $this->companyId);
        }

        // Apply contact filter
        if ($this->contactId) {
            $query->where('contact_id', $this->contactId);
        }

        // Apply opportunity filter
        if ($this->opportunityId) {
            $query->where('opportunity_id', $this->opportunityId);
        }

        // Apply user filter
        if ($this->assignedTo) {
            $query->where('assigned_to', $this->assignedTo);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $tasks = $query->paginate(10);
        $companies = Company::orderBy('name')->get(['id', 'name']);
        $contacts = Contact::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
        $opportunities = Opportunity::orderBy('title')->get(['id', 'title']);
        $users = User::orderBy('name')->get(['id', 'name']);
        $statusOptions = Task::getStatusOptions();
        $priorityOptions = Task::getPriorityOptions();
        $dateRangeOptions = [
            'today' => 'Due Today',
            'overdue' => 'Overdue',
            'upcoming' => 'Next 7 Days',
            'next-30-days' => 'Next 30 Days',
        ];

        return view('livewire.admin.crm.task-manager', [
            'tasks' => $tasks,
            'companies' => $companies,
            'contacts' => $contacts,
            'opportunities' => $opportunities,
            'users' => $users,
            'statusOptions' => $statusOptions,
            'priorityOptions' => $priorityOptions,
            'dateRangeOptions' => $dateRangeOptions,
        ]);
    }
} 