<?php

declare(strict_types=1);

namespace App\Livewire\Admin\JobBoard;

use App\Models\JobBoard\Job;
use App\Services\JobBoard\JobService;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class JobManager extends Component
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
     * Category filter.
     *
     * @var int|null
     */
    public ?int $categoryId = null;

    /**
     * Sort field.
     *
     * @var string
     */
    public string $sortField = 'title';

    /**
     * Sort direction.
     *
     * @var string
     */
    public string $sortDirection = 'asc';

    /**
     * Job being deleted.
     *
     * @var Job|null
     */
    public ?Job $deletingJob = null;

    /**
     * Reset the pagination when filters change.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset the pagination when status changes.
     */
    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Reset the pagination when category changes.
     */
    public function updatedCategoryId(): void
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
     * Set the job to be deleted.
     *
     * @param Job $job
     * @return void
     */
    public function confirmJobDeletion(Job $job): void
    {
        $this->deletingJob = $job;
    }

    /**
     * Delete the job.
     *
     * @param JobService $jobService
     * @return void
     */
    public function deleteJob(JobService $jobService): void
    {
        if ($this->deletingJob) {
            $jobService->deleteJob($this->deletingJob);
            $this->deletingJob = null;
            session()->flash('success', 'Job deleted successfully.');
        }
    }

    /**
     * Cancel job deletion.
     *
     * @return void
     */
    public function cancelDeletion(): void
    {
        $this->deletingJob = null;
    }

    /**
     * Toggle job featured status.
     *
     * @param Job $job
     * @param JobService $jobService
     * @return void
     */
    public function toggleFeatured(Job $job, JobService $jobService): void
    {
        $jobService->updateJob($job, [
            'is_featured' => !$job->is_featured,
        ]);

        session()->flash('success', $job->is_featured 
            ? 'Job removed from featured listings.' 
            : 'Job added to featured listings.');
    }

    /**
     * Toggle job active status.
     *
     * @param Job $job
     * @param JobService $jobService
     * @return void
     */
    public function toggleActive(Job $job, JobService $jobService): void
    {
        $jobService->updateJob($job, [
            'is_active' => !$job->is_active,
        ]);

        session()->flash('success', $job->is_active 
            ? 'Job deactivated successfully.' 
            : 'Job activated successfully.');
    }

    /**
     * Render the component.
     *
     * @param JobService $jobService
     * @return View
     */
    public function render(JobService $jobService): View
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->status,
            'category_id' => $this->categoryId,
            'sort_field' => $this->sortField,
            'sort_direction' => $this->sortDirection,
        ];

        $jobs = $jobService->getPaginatedJobs($filters);
        $categories = $jobService->getAllCategories();
        $statusOptions = Job::getStatusOptions();

        return view('livewire.admin.jobboard.job-manager', [
            'jobs' => $jobs,
            'categories' => $categories,
            'statusOptions' => $statusOptions,
        ]);
    }
} 