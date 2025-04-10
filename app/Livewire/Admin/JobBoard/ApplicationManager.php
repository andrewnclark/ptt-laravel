<?php

declare(strict_types=1);

namespace App\Livewire\Admin\JobBoard;

use App\Models\JobBoard\JobApplication;
use App\Models\JobBoard\Job;
use App\Models\Ats\Candidate;
use App\Services\JobBoard\JobService;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationManager extends Component
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
     * Job filter.
     *
     * @var int|null
     */
    public ?int $jobId = null;

    /**
     * Sort field.
     *
     * @var string
     */
    public string $sortField = 'created_at';

    /**
     * Sort direction.
     *
     * @var string
     */
    public string $sortDirection = 'desc';

    /**
     * Application being deleted.
     *
     * @var JobApplication|null
     */
    public ?JobApplication $deletingApplication = null;

    /**
     * Application being viewed.
     *
     * @var JobApplication|null
     */
    public ?JobApplication $viewingApplication = null;

    /**
     * Show status change modal.
     *
     * @var bool
     */
    public bool $showStatusModal = false;

    /**
     * Application being edited.
     *
     * @var JobApplication|null
     */
    public ?JobApplication $editingApplication = null;

    /**
     * New status value.
     *
     * @var string
     */
    public string $newStatus = '';

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
     * Reset pagination when job filter changes.
     */
    public function updatedJobId(): void
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
     * Set the application to be deleted.
     *
     * @param JobApplication $application
     * @return void
     */
    public function confirmApplicationDeletion(JobApplication $application): void
    {
        $this->deletingApplication = $application;
    }

    /**
     * Delete the application.
     *
     * @return void
     */
    public function deleteApplication(): void
    {
        if ($this->deletingApplication) {
            $this->deletingApplication->delete();
            $this->deletingApplication = null;
            session()->flash('success', 'Application deleted successfully.');
        }
    }

    /**
     * Cancel application deletion.
     *
     * @return void
     */
    public function cancelDeletion(): void
    {
        $this->deletingApplication = null;
    }

    /**
     * View application details.
     *
     * @param JobApplication $application
     * @return void
     */
    public function viewApplication(JobApplication $application): void
    {
        $this->viewingApplication = $application;
        $application->markAsViewed();
    }

    /**
     * Close application view.
     *
     * @return void
     */
    public function closeApplicationView(): void
    {
        $this->viewingApplication = null;
    }

    /**
     * Open status change modal.
     *
     * @param JobApplication $application
     * @return void
     */
    public function openStatusModal(JobApplication $application): void
    {
        $this->editingApplication = $application;
        $this->newStatus = $application->status;
        $this->showStatusModal = true;
    }

    /**
     * Change application status.
     *
     * @return void
     */
    public function changeStatus(): void
    {
        if ($this->editingApplication) {
            $this->editingApplication->update([
                'status' => $this->newStatus,
            ]);

            // If viewing the application, refresh the view
            if ($this->viewingApplication && $this->viewingApplication->id === $this->editingApplication->id) {
                $this->viewingApplication = JobApplication::find($this->viewingApplication->id);
            }

            $this->showStatusModal = false;
            $this->editingApplication = null;
            session()->flash('success', 'Application status updated successfully.');
        }
    }

    /**
     * Convert application to candidate.
     *
     * @param JobApplication $application
     * @return void
     */
    public function convertToCandidate(JobApplication $application): void
    {
        // Check if candidate already exists
        $candidate = Candidate::where('email', $application->email)->first();

        if (!$candidate) {
            // Create new candidate
            $candidate = Candidate::create([
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'resume_path' => $application->resume_path,
                'status' => Candidate::STATUS_NEW,
                'source' => 'job_application',
            ]);
        }

        // Link application to candidate if not already linked
        if (!$application->candidate_id) {
            $application->update([
                'candidate_id' => $candidate->id,
            ]);

            // If viewing the application, refresh the view
            if ($this->viewingApplication && $this->viewingApplication->id === $application->id) {
                $this->viewingApplication = JobApplication::find($this->viewingApplication->id);
            }

            session()->flash('success', 'Application converted to candidate successfully.');
        } else {
            session()->flash('info', 'This application is already linked to a candidate.');
        }
    }

    /**
     * Render the component.
     *
     * @param JobService $jobService
     * @return View
     */
    public function render(JobService $jobService): View
    {
        $query = JobApplication::query()->with(['job']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhereHas('job', function ($q) {
                      $q->where('title', 'like', "%{$this->search}%");
                  });
            });
        }

        // Apply status filter
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        // Apply job filter
        if ($this->jobId) {
            $query->where('job_id', $this->jobId);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $applications = $query->paginate(10);
        $jobs = Job::select('id', 'title')->orderBy('title')->get();
        $statusOptions = JobApplication::getStatusOptions();

        return view('livewire.admin.jobboard.application-manager', [
            'applications' => $applications,
            'jobs' => $jobs,
            'statusOptions' => $statusOptions,
        ]);
    }
} 