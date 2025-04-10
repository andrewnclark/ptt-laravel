<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Contact $contact;
    protected Opportunity $opportunity;
    protected Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->contact = Contact::factory()->create(['company_id' => $this->company->id]);
        $this->opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
        ]);
        $this->task = Task::factory()->create([
            'user_id' => $this->user->id,
            'assigned_to' => $this->user->id,
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'opportunity_id' => $this->opportunity->id,
            'status' => Task::STATUS_NOT_STARTED,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->addDays(5),
        ]);
    }

    #[Test]
    public function it_can_record_activity_when_task_is_created()
    {
        // The task is created in setUp(), so we can verify the activity was recorded
        $activity = $this->task->activities()->where('type', Activity::TYPE_CREATED)->first();
        
        $this->assertNotNull($activity);
        $this->assertEquals('Created Task', $activity->description);
        $this->assertTrue($activity->is_system_generated);
    }

    #[Test]
    public function it_can_complete_a_task()
    {
        // When
        $result = $this->task->complete();
        $this->task->refresh();

        // Then
        $this->assertTrue($result);
        $this->assertEquals(Task::STATUS_COMPLETED, $this->task->status);
        $this->assertNotNull($this->task->completed_at);
        
        // Verify activity was recorded
        $activity = $this->task->activities()->where('type', Activity::TYPE_TASK_COMPLETED)->first();
        $this->assertNotNull($activity);
        $this->assertEquals("Task completed: {$this->task->title}", $activity->description);
        $this->assertEquals($this->task->id, $activity->properties['task_id']);
    }

    #[Test]
    public function completing_a_task_records_activities_for_related_entities()
    {
        // When
        $this->task->complete();

        // Then - Company should have activity
        $companyActivity = $this->company->activities()
            ->where('type', Activity::TYPE_TASK_COMPLETED)
            ->first();
        $this->assertNotNull($companyActivity);
        $this->assertEquals("Task completed: {$this->task->title}", $companyActivity->description);

        // Then - Contact should have activity
        $contactActivity = $this->contact->activities()
            ->where('type', Activity::TYPE_TASK_COMPLETED)
            ->first();
        $this->assertNotNull($contactActivity);
        $this->assertEquals("Task completed: {$this->task->title}", $contactActivity->description);

        // Then - Opportunity should have activity
        $opportunityActivity = $this->opportunity->activities()
            ->where('type', Activity::TYPE_TASK_COMPLETED)
            ->first();
        $this->assertNotNull($opportunityActivity);
        $this->assertEquals("Task completed: {$this->task->title}", $opportunityActivity->description);
    }

    #[Test]
    public function completing_an_already_completed_task_returns_true_without_duplicating_activities()
    {
        // Given
        $this->task->complete();
        $initialActivityCount = $this->task->activities()->count();

        // When - Complete again
        $result = $this->task->complete();

        // Then
        $this->assertTrue($result);
        // No new activities should be created
        $this->assertEquals($initialActivityCount, $this->task->activities()->count());
    }

    #[Test]
    public function it_can_get_tasks_with_status_scope()
    {
        // Given
        Task::factory()->create(['status' => Task::STATUS_IN_PROGRESS]);
        Task::factory()->create(['status' => Task::STATUS_COMPLETED]);
        
        // When & Then
        $this->assertEquals(1, Task::withStatus(Task::STATUS_NOT_STARTED)->count());
        $this->assertEquals(1, Task::withStatus(Task::STATUS_IN_PROGRESS)->count());
        $this->assertEquals(1, Task::withStatus(Task::STATUS_COMPLETED)->count());
    }

    #[Test]
    public function it_can_get_incomplete_tasks()
    {
        // Given
        Task::factory()->create(['status' => Task::STATUS_IN_PROGRESS]);
        Task::factory()->create(['status' => Task::STATUS_COMPLETED]);
        Task::factory()->create(['status' => Task::STATUS_CANCELLED]);
        
        // When & Then
        $this->assertEquals(2, Task::incomplete()->count());
    }

    #[Test]
    public function it_can_get_completed_tasks()
    {
        // Given
        Task::factory()->create(['status' => Task::STATUS_COMPLETED]);
        Task::factory()->create(['status' => Task::STATUS_COMPLETED]);
        
        // When & Then
        $this->assertEquals(2, Task::completed()->count());
    }

    #[Test]
    public function it_can_get_overdue_tasks()
    {
        // Given
        $overdueTask = Task::factory()->create([
            'due_date' => now()->subDay(),
            'status' => Task::STATUS_NOT_STARTED
        ]);
        Task::factory()->create([
            'due_date' => now()->addDay(),
            'status' => Task::STATUS_NOT_STARTED
        ]);
        
        // When & Then
        $this->assertEquals(1, Task::where('id', $overdueTask->id)->overdue()->count());
    }

    #[Test]
    public function it_can_get_tasks_due_today()
    {
        // Given
        Task::factory()->create([
            'due_date' => now(),
            'status' => Task::STATUS_NOT_STARTED
        ]);
        Task::factory()->create([
            'due_date' => now()->addDay(),
            'status' => Task::STATUS_NOT_STARTED
        ]);
        
        // When & Then
        $this->assertEquals(1, Task::dueToday()->count());
    }

    #[Test]
    public function it_can_get_tasks_with_priority()
    {
        // Given
        $highPriorityTask = Task::factory()->create(['priority' => Task::PRIORITY_HIGH]);
        
        // When & Then
        $this->assertEquals(1, Task::where('id', $highPriorityTask->id)->withPriority(Task::PRIORITY_HIGH)->count());
        $this->assertEquals(1, Task::where('id', $this->task->id)->withPriority(Task::PRIORITY_MEDIUM)->count());
    }

    #[Test]
    public function it_can_get_tasks_assigned_to_user()
    {
        // Given
        $anotherUser = User::factory()->create();
        Task::factory()->create(['assigned_to' => $anotherUser->id]);
        
        // When & Then
        $this->assertEquals(1, Task::where('assigned_to', $this->user->id)->count());
        $this->assertEquals(1, Task::where('assigned_to', $anotherUser->id)->count());
    }
} 