<?php

declare(strict_types=1);

namespace App\Services\JobBoard;

use App\Models\JobBoard\Job;
use App\Models\JobBoard\JobCategory;
use App\Models\JobBoard\JobApplication;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class JobService
{
    /**
     * Get paginated jobs with filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getPaginatedJobs(array $filters = []): LengthAwarePaginator
    {
        $query = Job::query()
            ->with(['company', 'category']);

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply category filter
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Apply active filter
        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        // Apply sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate(10);
    }

    /**
     * Get a job by its ID.
     *
     * @param int $id
     * @return Job|null
     */
    public function getJobById(int $id): ?Job
    {
        return Job::with(['company', 'category'])->find($id);
    }

    /**
     * Create a new job.
     *
     * @param array $data
     * @return Job
     */
    public function createJob(array $data): Job
    {
        // Generate a slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return Job::create($data);
    }

    /**
     * Update a job.
     *
     * @param Job $job
     * @param array $data
     * @return bool
     */
    public function updateJob(Job $job, array $data): bool
    {
        // Update the slug if title changed and slug was not manually set
        if (isset($data['title']) && $job->title !== $data['title'] && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $job->update($data);
    }

    /**
     * Delete a job.
     *
     * @param Job $job
     * @return bool
     */
    public function deleteJob(Job $job): bool
    {
        return $job->delete();
    }

    /**
     * Get all job categories.
     *
     * @param bool $activeOnly
     * @return Collection
     */
    public function getAllCategories(bool $activeOnly = false): Collection
    {
        $query = JobCategory::query()->sorted();
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->get();
    }

    /**
     * Get recent job applications.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentApplications(int $limit = 5): Collection
    {
        return JobApplication::with(['job'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get active jobs count.
     *
     * @return int
     */
    public function getActiveJobsCount(): int
    {
        return Job::published()->count();
    }

    /**
     * Get featured jobs.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedJobs(int $limit = 6): Collection
    {
        return Job::with(['company', 'category'])
            ->published()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get jobs by category.
     *
     * @param int $categoryId
     * @param int $limit
     * @return Collection
     */
    public function getJobsByCategory(int $categoryId, int $limit = 10): Collection
    {
        return Job::with(['company'])
            ->published()
            ->where('category_id', $categoryId)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
} 