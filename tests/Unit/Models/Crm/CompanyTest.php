<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create([
            'name' => 'Acme Corp',
            'status' => Company::STATUS_LEAD
        ]);
        $this->task = Task::factory()->create([
            'user_id' => $this->user->id,
            'assigned_to' => $this->user->id,
            'company_id' => $this->company->id,
            'status' => Task::STATUS_NOT_STARTED,
            'priority' => Task::PRIORITY_MEDIUM,
        ]);
        
        $this->actingAs($this->user);
    }

    #[Test]
    public function it_has_activities_relationship()
    {
        $this->assertInstanceOf(MorphMany::class, $this->company->activities());
    }

    #[Test]
    public function it_can_record_activity()
    {
        // When
        $activity = $this->company->recordActivity(
            Activity::TYPE_NOTE_ADDED,
            'Test note for company',
            ['note' => 'Test note for company']
        );
        
        // Then
        $this->assertNotNull($activity);
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activity->type);
        $this->assertEquals('Test note for company', $activity->description);
    }

    #[Test]
    public function it_can_add_a_note()
    {
        // When
        $note = 'This is a test note for company';
        $activity = $this->company->addNote($note);
        
        // Then
        $this->assertNotNull($activity);
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activity->type);
        $this->assertEquals($note, $activity->description);
        $this->assertFalse($activity->is_system_generated);
    }

    #[Test]
    public function it_records_activity_when_created()
    {
        // Given - A new company is created
        $newCompany = Company::factory()->create([
            'name' => 'New Test Company',
            'status' => Company::STATUS_PROSPECT
        ]);
        
        // Then - An activity should be recorded
        $activity = $newCompany->activities()->where('type', Activity::TYPE_CREATED)->first();
        $this->assertNotNull($activity);
        $this->assertEquals('Created Company', $activity->description);
        $this->assertTrue($activity->is_system_generated);
    }

    #[Test]
    public function it_records_activity_when_status_changes()
    {
        // When - Change company status
        $this->company->changeStatus(Company::STATUS_PROSPECT);
        
        // Then
        $activity = $this->company->activities()->where('type', Activity::TYPE_STATUS_CHANGED)->first();
        $this->assertNotNull($activity);
        $this->assertStringContainsString('Status changed from', $activity->description);
        $this->assertEquals(Company::STATUS_LEAD, $activity->properties['old_status']);
        $this->assertEquals(Company::STATUS_PROSPECT, $activity->properties['new_status']);
    }

    #[Test]
    public function it_receives_activity_when_related_task_is_completed()
    {
        // When - Complete the task
        $this->task->complete();
        
        // Then - Company should have the activity
        $activity = $this->company->activities()
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
            $this->company->recordActivity(
                Activity::TYPE_NOTE_ADDED,
                "Test note {$i}",
                ['note' => "Test note {$i}"]
            );
        }
        
        // When - Get recent activities with limit
        $recentActivities = $this->company->getRecentActivities(3);
        
        // Then - Should have the correct number of activities
        $this->assertCount(3, $recentActivities);
    }

    #[Test]
    public function it_records_activity_when_deleted()
    {
        // Given
        $companyId = $this->company->id;
        
        // When
        $this->company->delete();
        
        // Then
        $this->assertSoftDeleted($this->company);
        
        $activity = Activity::where('subject_id', $companyId)
            ->where('subject_type', Company::class)
            ->where('type', Activity::TYPE_DELETED)
            ->first();
            
        $this->assertNotNull($activity);
        $this->assertEquals('Deleted Company', $activity->description);
    }
} 