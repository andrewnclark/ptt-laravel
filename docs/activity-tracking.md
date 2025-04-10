# Activity Tracking Documentation

## Overview

The PTT Laravel CRM system implements a comprehensive activity tracking system that records all significant events and changes in the system. This document details the implementation, usage, and best practices for activity tracking.

## Core Components

### 1. Activity Model

The central model for storing all system activities.

```php
namespace App\Models\Crm;

class Activity extends Model
{
    protected $table = 'crm_activities';

    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'type',
        'description',
        'properties',
        'is_system_generated',
    ];

    protected $casts = [
        'properties' => 'array',
        'is_system_generated' => 'boolean',
    ];
}
```

### 2. HasActivities Trait

Provides activity recording capabilities to models.

```php
namespace App\Traits;

trait HasActivities
{
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function recordActivity(
        string $type,
        string $description,
        array $properties = [],
        bool $isSystemGenerated = true
    ): Activity {
        return $this->activities()->create([
            'user_id' => Auth::id() ?? 1,
            'type' => $type,
            'description' => $description,
            'properties' => $properties,
            'is_system_generated' => $isSystemGenerated,
        ]);
    }
}
```

## Activity Types

### 1. Standard Activity Types

```php
class Activity
{
    public const TYPE_CREATED = 'created';
    public const TYPE_UPDATED = 'updated';
    public const TYPE_DELETED = 'deleted';
    public const TYPE_NOTE_ADDED = 'note_added';
    public const TYPE_EMAIL_SENT = 'email_sent';
    public const TYPE_TASK_CREATED = 'task_created';
    public const TYPE_TASK_COMPLETED = 'task_completed';
    public const TYPE_STATUS_CHANGED = 'status_changed';
    public const TYPE_STAGE_CHANGED = 'stage_changed';
    public const TYPE_CUSTOM = 'custom';
}
```

### 2. Custom Activity Types

You can define custom activity types for specific business needs:

```php
class CustomActivityTypes
{
    public const TYPE_CONTACT_PRIMARY_CHANGED = 'contact_primary_changed';
    public const TYPE_COMPANY_MERGED = 'company_merged';
    public const TYPE_DOCUMENT_UPLOADED = 'document_uploaded';
}
```

## Implementation Examples

### 1. Model Integration

```php
namespace App\Models\Crm;

class Contact extends Model
{
    use HasActivities;

    public function setAsPrimary(): bool
    {
        // First, un-set any existing primary contacts
        $this->company->contacts()
            ->where('id', '!=', $this->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
        
        // Set this contact as primary
        $this->is_primary = true;
        $saved = $this->save();
        
        if ($saved) {
            $this->recordActivity(
                Activity::TYPE_UPDATED,
                "Contact {$this->full_name} was set as primary contact",
                [
                    'company_id' => $this->company_id,
                    'changed_attributes' => ['is_primary' => true],
                ]
            );
        }
        
        return $saved;
    }
}
```

### 2. Observer Implementation

```php
namespace App\Observers;

class ContactObserver
{
    public function created(Contact $contact): void
    {
        $contact->recordActivity(
            Activity::TYPE_CREATED,
            "Contact {$contact->full_name} was created",
            [
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'email' => $contact->email,
                'company_id' => $contact->company_id,
                'is_primary' => $contact->is_primary,
            ]
        );
    }

    public function updated(Contact $contact): void
    {
        if (empty($contact->getDirty())) {
            return;
        }

        $contact->recordActivity(
            Activity::TYPE_UPDATED,
            "Contact {$contact->full_name} was updated",
            [
                'company_id' => $contact->company_id,
                'changed_attributes' => $contact->getDirty(),
            ]
        );
    }
}
```

## Activity Retrieval

### 1. Basic Queries

```php
// Get all activities for a model
$contact->activities;

// Get recent activities
$contact->getRecentActivities(10);

// Get activities by type
$contact->activities()->ofType(Activity::TYPE_UPDATED)->get();

// Get system-generated activities
$contact->activities()->systemGenerated()->get();
```

### 2. Advanced Queries

```php
// Get activities with related data
$activities = Activity::with(['user', 'subject'])
    ->where('subject_type', Contact::class)
    ->where('subject_id', $contact->id)
    ->latest()
    ->get();

// Get activities by date range
$activities = Activity::whereBetween('created_at', [
    now()->subDays(7),
    now()
])->get();

// Get activities by user
$activities = Activity::where('user_id', $userId)
    ->with('subject')
    ->get();
```

## Activity Display

### 1. Activity Feed Component

```php
namespace App\Livewire;

class ActivityFeed extends Component
{
    public $subject;
    public $activities;

    public function mount($subject)
    {
        $this->subject = $subject;
        $this->loadActivities();
    }

    public function loadActivities()
    {
        $this->activities = $this->subject
            ->activities()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.activity-feed');
    }
}
```

### 2. Activity Feed View

```blade
<div class="space-y-4">
    @foreach($activities as $activity)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    @if($activity->user)
                        <img class="h-8 w-8 rounded-full" 
                             src="{{ $activity->user->profile_photo_url }}" 
                             alt="{{ $activity->user->name }}">
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $activity->description }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $activity->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
```

## Best Practices

### 1. Activity Recording
- Record meaningful activities only
- Include relevant context in properties
- Use appropriate activity types
- Handle exceptions gracefully

### 2. Performance
- Index the activities table properly
- Use eager loading for related data
- Implement pagination for large datasets
- Cache frequently accessed activities

### 3. Security
- Validate user permissions
- Sanitize activity data
- Log security-related activities
- Implement proper access control

### 4. Maintenance
- Archive old activities
- Clean up unnecessary activities
- Monitor activity table size
- Implement proper backup strategy

## Troubleshooting

### 1. Common Issues
- Duplicate activities
- Missing activities
- Performance issues
- Data inconsistency

### 2. Debugging
- Check activity logs
- Verify observer registration
- Monitor database queries
- Review activity triggers

### 3. Optimization
- Implement proper indexing
- Use database partitioning
- Optimize queries
- Implement caching strategy 