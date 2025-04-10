<?php

declare(strict_types=1);

namespace App\Services\Crm;

use App\Models\Crm\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    /**
     * Get all activities for a given subject.
     *
     * @param Model $subject
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getActivitiesForSubject(Model $subject, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Activity::query()
            ->with(['user', 'causer'])
            ->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey());
        
        // Apply filters
        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['user_id']) && $filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['is_system_generated']) && is_bool($filters['is_system_generated'])) {
            $query->where('is_system_generated', $filters['is_system_generated']);
        }
        
        return $query->latest()->paginate($perPage);
    }
    
    /**
     * Record an activity for a subject.
     *
     * @param Model $subject
     * @param string $type
     * @param string $description
     * @param array $properties
     * @param Model|null $causer
     * @param User|null $user
     * @param bool $isSystemGenerated
     * @return Activity
     */
    public function recordActivity(
        Model $subject,
        string $type,
        string $description,
        array $properties = [],
        ?Model $causer = null,
        ?User $user = null,
        bool $isSystemGenerated = true
    ): Activity {
        return Activity::create([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->getKey(),
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer ? $causer->getKey() : null,
            'user_id' => $user ? $user->id : (Auth::id() ?? 1),
            'type' => $type,
            'description' => $description,
            'properties' => $properties,
            'is_system_generated' => $isSystemGenerated,
        ]);
    }
    
    /**
     * Get activity types with counts for a subject.
     *
     * @param Model $subject
     * @return Collection
     */
    public function getActivityTypeStats(Model $subject): Collection
    {
        return Activity::where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey())
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();
    }
    
    /**
     * Get recent activities across the system.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentActivities(int $limit = 10): Collection
    {
        return Activity::with(['user', 'subject'])
            ->latest()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get activities for a specific user.
     *
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getActivitiesForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Activity::with(['subject'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Add a note to a subject.
     *
     * @param Model $subject
     * @param string $note
     * @param User|null $user
     * @return Activity
     */
    public function addNote(Model $subject, string $note, ?User $user = null): Activity
    {
        return $this->recordActivity(
            $subject,
            Activity::TYPE_NOTE_ADDED,
            $note,
            [],
            null,
            $user,
            false // User generated
        );
    }
} 