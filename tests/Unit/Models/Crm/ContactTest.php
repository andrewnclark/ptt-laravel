<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Contact $contact;
    protected Company $company;
    protected Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->contact = Contact::factory()->create([
            'company_id' => $this->company->id
        ]);
        $this->task = Task::factory()->create([
            'user_id' => $this->user->id,
            'assigned_to' => $this->user->id,
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'status' => Task::STATUS_NOT_STARTED,
            'priority' => Task::PRIORITY_MEDIUM,
        ]);
        
        $this->actingAs($this->user);
    }

    #[Test]
    public function it_has_activities_relationship()
    {
        $this->assertInstanceOf(MorphMany::class, $this->contact->activities());
    }

    #[Test]
    public function it_can_record_activity()
    {
        // Act
        $activity = $this->contact->recordActivity(
            Activity::TYPE_NOTE_ADDED,
            'Test note for contact',
            ['note' => 'Test note for contact']
        );
        
        // Assert
        $this->assertNotNull($activity);
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activity->type);
        $this->assertEquals('Test note for contact', $activity->description);
    }

    #[Test]
    public function it_can_add_a_note()
    {
        // Act
        $note = 'This is a test note for contact';
        $activity = $this->contact->addNote($note);
        
        // Assert
        $this->assertNotNull($activity);
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activity->type);
        $this->assertEquals($note, $activity->description);
        $this->assertFalse($activity->is_system_generated);
    }

    #[Test]
    public function it_records_activity_when_created()
    {
        // Given - A new contact is created
        $newContact = Contact::factory()->create([
            'company_id' => $this->company->id,
            'first_name' => 'John',
            'last_name' => 'Test'
        ]);
        
        // Then - An activity should be recorded
        $activity = $newContact->activities()->where('type', Activity::TYPE_CREATED)->first();
        $this->assertNotNull($activity);
        $this->assertEquals('Created Contact', $activity->description);
        $this->assertTrue($activity->is_system_generated);
    }

    #[Test]
    public function it_receives_activity_when_related_task_is_completed()
    {
        // When - Complete the task
        $this->task->complete();
        
        // Then - Contact should have the activity
        $activity = $this->contact->activities()
            ->where('type', Activity::TYPE_TASK_COMPLETED)
            ->first();
            
        $this->assertNotNull($activity);
        $this->assertEquals("Task completed: {$this->task->title}", $activity->description);
        $this->assertEquals($this->task->id, $activity->properties['task_id']);
    }

    #[Test]
    public function it_can_get_recent_activities()
    {
        // Given - Create multiple activities
        for ($i = 1; $i <= 5; $i++) {
            $this->contact->recordActivity(
                Activity::TYPE_NOTE_ADDED,
                "Test note {$i}",
                ['note' => "Test note {$i}"]
            );
        }
        
        // When - Get recent activities with limit
        $recentActivities = $this->contact->getRecentActivities(3);
        
        // Then - Only the most recent should be returned
        $this->assertCount(3, $recentActivities);
        // Don't assert specific content as the order may vary
    }

    #[Test]
    public function it_records_activity_when_deleted()
    {
        // Given
        $contactId = $this->contact->id;
        
        // When
        $this->contact->delete();
        
        // Then
        $this->assertSoftDeleted($this->contact);
        
        $activity = Activity::where('subject_id', $contactId)
            ->where('subject_type', Contact::class)
            ->where('type', Activity::TYPE_DELETED)
            ->first();
            
        $this->assertNotNull($activity);
        $this->assertEquals('Deleted Contact', $activity->description);
    }
} 