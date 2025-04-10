<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Crm\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait HasActivities
{
    /**
     * Flag to prevent duplicate activity recording
     * 
     * @var array
     */
    protected static $activityRecorded = [];
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootHasActivities(): void
    {
        // The events are now handled by the dedicated observer classes
        // This prevents duplicate activity recording
    }

    /**
     * Get all activities for this model.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Record a new activity for this model.
     *
     * @param string $type
     * @param string $description
     * @param array $properties
     * @param bool $isSystemGenerated
     * @return Activity
     */
    public function recordActivity(
        string $type,
        string $description,
        array $properties = [],
        bool $isSystemGenerated = true
    ): Activity {
        $userId = Auth::id() ?? 1; // Default to admin user if none logged in
        
        // Generate a unique key for this activity to prevent duplicates
        $key = md5($this->getMorphClass() . $this->getKey() . $type . json_encode($properties));
        
        // Check if we've already recorded this exact activity recently
        if (isset(static::$activityRecorded[$key]) && static::$activityRecorded[$key] > time() - 5) {
            Log::info('Prevented duplicate activity record', [
                'subject_type' => get_class($this),
                'subject_id' => $this->getKey(),
                'type' => $type,
                'key' => $key
            ]);
            
            // Return the first activity that matches these criteria
            $existingActivity = $this->activities()
                ->where('type', $type)
                ->where('created_at', '>', now()->subSeconds(5))
                ->first();
                
            if ($existingActivity) {
                return $existingActivity;
            }
        }
        
        // Mark this activity as recorded with the current timestamp
        static::$activityRecorded[$key] = time();
        
        Log::debug('Recording activity', [
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'type' => $type,
            'user_id' => $userId,
            'auth_check' => Auth::check(),
        ]);
        
        return $this->activities()->create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'properties' => $properties,
            'is_system_generated' => $isSystemGenerated,
        ]);
    }

    /**
     * Add a custom note activity.
     *
     * @param string $note
     * @param array $properties
     * @return Activity
     */
    public function addNote(string $note, array $properties = []): Activity
    {
        return $this->recordActivity(
            Activity::TYPE_NOTE_ADDED,
            $note,
            $properties,
            false // User generated
        );
    }

    /**
     * Get the recent activities for this model.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivities(int $limit = 10)
    {
        return $this->activities()
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
} 