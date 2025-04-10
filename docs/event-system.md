# Event System Documentation

## Overview

The PTT Laravel CRM system implements a robust event-driven architecture using Laravel's event system. This document details the implementation, usage, and best practices for the event system.

## Event System Components

### 1. Events

Events are simple data containers that represent something that happened in the system.

#### Example Event:
```php
namespace App\Events\Crm;

use App\Models\Crm\Contact;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactSetAsPrimary
{
    use Dispatchable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }
}
```

#### Best Practices:
- Keep events simple and focused
- Include only necessary data
- Use type hints for properties
- Implement SerializesModels trait for queue support

### 2. Listeners

Listeners handle the business logic triggered by events.

#### Example Listener:
```php
namespace App\Listeners\Crm;

use App\Events\Crm\ContactSetAsPrimary;
use App\Models\Crm\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleContactSetAsPrimary implements ShouldQueue
{
    public function handle(ContactSetAsPrimary $event): void
    {
        $contact = $event->contact;
        
        // Record activity
        $contact->recordActivity(
            Activity::TYPE_UPDATED,
            "Primary contact changed to {$contact->full_name}"
        );
        
        // Send notifications
        $this->sendNotifications($contact);
    }
}
```

#### Best Practices:
- Implement ShouldQueue for long-running tasks
- Keep listeners focused on a single responsibility
- Handle exceptions appropriately
- Log important operations

### 3. Event Registration

Events and listeners are registered in the EventServiceProvider.

#### Example Registration:
```php
namespace App\Providers;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ContactSetAsPrimary::class => [
            HandleContactSetAsPrimary::class,
        ],
    ];
}
```

## Event Types

### 1. Model Events

Automatically triggered by model lifecycle events.

#### Common Model Events:
- created
- updated
- deleted
- restored
- forceDeleted

### 2. Custom Events

User-defined events for specific business logic.

#### Example Custom Events:
- ContactSetAsPrimary
- CompanyStatusChanged
- TaskCompleted
- NoteAdded

## Event Handling Patterns

### 1. Activity Recording

```php
// In a model observer
public function updated(Model $model): void
{
    if ($model->isDirty('is_primary') && $model->is_primary) {
        event(new ContactSetAsPrimary($model));
    }
}
```

### 2. Notification Dispatching

```php
// In an event listener
protected function sendNotifications($contact): void
{
    Notification::send(
        $contact->company->account_manager,
        new ContactSetAsPrimaryNotification($contact)
    );
}
```

### 3. Cache Invalidation

```php
// In an event listener
protected function invalidateCache($model): void
{
    Cache::tags([$model->getTable()])->flush();
}
```

## Queue Integration

### 1. Queue Configuration

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
    ],
],
```

### 2. Queue Implementation

```php
class HandleContactSetAsPrimary implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $maxExceptions = 3;
    public $timeout = 120;

    public function failed($event, $exception): void
    {
        Log::error('Failed to handle ContactSetAsPrimary event', [
            'contact_id' => $event->contact->id,
            'error' => $exception->getMessage()
        ]);
    }
}
```

## Testing Events

### 1. Event Testing

```php
public function test_contact_set_as_primary_dispatches_event(): void
{
    Event::fake();
    
    $contact = Contact::factory()->create();
    $contact->setAsPrimary();
    
    Event::assertDispatched(ContactSetAsPrimary::class);
}
```

### 2. Listener Testing

```php
public function test_handle_contact_set_as_primary_records_activity(): void
{
    $contact = Contact::factory()->create();
    $event = new ContactSetAsPrimary($contact);
    
    $listener = new HandleContactSetAsPrimary();
    $listener->handle($event);
    
    $this->assertDatabaseHas('activities', [
        'subject_type' => Contact::class,
        'subject_id' => $contact->id,
        'type' => Activity::TYPE_UPDATED
    ]);
}
```

## Best Practices

### 1. Event Naming
- Use past tense for event names (e.g., ContactSetAsPrimary)
- Be specific about what happened
- Follow consistent naming conventions

### 2. Listener Organization
- Group listeners by domain
- Keep listeners focused and simple
- Implement proper error handling
- Use dependency injection

### 3. Performance Considerations
- Queue long-running listeners
- Implement proper retry logic
- Monitor queue performance
- Use appropriate queue drivers

### 4. Debugging
- Log important events
- Implement proper error handling
- Use Laravel's event debugging tools
- Monitor queue failures

## Common Patterns

### 1. Event Broadcasting
```php
class ContactSetAsPrimary implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('company.' . $this->contact->company_id),
        ];
    }
}
```

### 2. Event Subscribers
```php
class ContactEventSubscriber
{
    public function handleContactSetAsPrimary($event): void
    {
        // Handle the event
    }

    public function subscribe($events): void
    {
        $events->listen(
            ContactSetAsPrimary::class,
            [ContactEventSubscriber::class, 'handleContactSetAsPrimary']
        );
    }
}
```

## Troubleshooting

### 1. Common Issues
- Events not being dispatched
- Listeners not being called
- Queue jobs failing
- Performance issues

### 2. Debugging Tools
- Laravel Telescope
- Queue monitoring
- Event logging
- Exception tracking

### 3. Performance Optimization
- Use appropriate queue drivers
- Implement proper indexing
- Monitor queue performance
- Use caching where appropriate 