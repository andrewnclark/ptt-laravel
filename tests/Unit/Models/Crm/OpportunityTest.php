<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\OpportunityStage;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OpportunityTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Contact $contact;
    protected Opportunity $opportunity;
    protected Task $task;
    protected OpportunityStage $stage;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->contact = Contact::factory()->create([
            'company_id' => $this->company->id
        ]);
        
        // Create a stage for the opportunity
        $this->stage = OpportunityStage::create([
            'name' => 'Initial Contact',
            'key' => 'initial_contact',
            'position' => 1,
            'probability' => 20,
            'is_won_stage' => false,
            'is_lost_stage' => false,
        ]);
        
        $this->opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'stage_id' => $this->stage->id,
            'status' => Opportunity::STATUS_NEW
        ]);
        
        $this->task = Task::factory()->create([
            'user_id' => $this->user->id,
            'assigned_to' => $this->user->id,
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'opportunity_id' => $this->opportunity->id,
            'status' => Task::STATUS_NOT_STARTED,
            'priority' => Task::PRIORITY_MEDIUM,
        ]);
        
        $this->actingAs($this->user);
    }

    #[Test]
    public function it_has_activities_relationship()
    {
        $this->assertInstanceOf(MorphMany::class, $this->opportunity->activities());
    }

    #[Test]
    public function it_can_record_activity()
    {
        // When
        $activity = $this->opportunity->recordActivity(
            Activity::TYPE_NOTE_ADDED,
            'Test note for opportunity',
            ['note' => 'Test note for opportunity']
        );
        
        // Then
        $this->assertNotNull($activity);
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activity->type);
        $this->assertEquals('Test note for opportunity', $activity->description);
    }

    #[Test]
    public function it_can_add_a_note()
    {
        // When
        $note = 'This is a test note for opportunity';
        $activity = $this->opportunity->addNote($note);
        
        // Then
        $this->assertNotNull($activity);
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activity->type);
        $this->assertEquals($note, $activity->description);
        $this->assertFalse($activity->is_system_generated);
    }

    #[Test]
    public function it_records_activity_when_created()
    {
        // Given - A new opportunity is created
        $newOpportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'contact_id' => $this->contact->id,
            'title' => 'Test Opportunity',
            'stage_id' => $this->stage->id
        ]);
        
        // Then - An activity should be recorded
        $activity = $newOpportunity->activities()->where('type', Activity::TYPE_CREATED)->first();
        $this->assertNotNull($activity);
        $this->assertEquals('Created Opportunity', $activity->description);
        $this->assertTrue($activity->is_system_generated);
    }

    #[Test]
    public function it_records_activity_when_stage_changes()
    {
        // Given - Create a new stage
        $newStage = OpportunityStage::create([
            'name' => 'Proposal',
            'key' => 'proposal_stage',
            'position' => 2,
            'probability' => 50,
            'is_won_stage' => false,
            'is_lost_stage' => false,
        ]);
        
        // When - Move to new stage
        $this->opportunity->moveToStage($newStage);
        
        // Then - Check activity was recorded
        $activity = $this->opportunity->activities()->where('type', Activity::TYPE_STAGE_CHANGED)->first();
        $this->assertNotNull($activity);
        $this->assertStringContainsString("Moved from", $activity->description);
        $this->assertEquals($this->stage->id, $activity->properties['old_stage_id']);
        $this->assertEquals($newStage->id, $activity->properties['new_stage_id']);
    }

    #[Test]
    public function it_receives_activity_when_related_task_is_completed()
    {
        // When - Complete the task
        $this->task->complete();
        
        // Then - Opportunity should have the activity
        $activity = $this->opportunity->activities()
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
            $this->opportunity->recordActivity(
                Activity::TYPE_NOTE_ADDED,
                "Test note {$i}",
                ['note' => "Test note {$i}"]
            );
        }
        
        // When - Get recent activities with limit
        $recentActivities = $this->opportunity->getRecentActivities(3);
        
        // Then - Should have the correct number of activities
        $this->assertCount(3, $recentActivities);
    }

    #[Test]
    public function it_records_activity_when_deleted()
    {
        // Given
        $opportunityId = $this->opportunity->id;
        
        // When
        $this->opportunity->delete();
        
        // Then
        $this->assertSoftDeleted($this->opportunity);
        
        $activity = Activity::where('subject_id', $opportunityId)
            ->where('subject_type', Opportunity::class)
            ->where('type', Activity::TYPE_DELETED)
            ->first();
            
        $this->assertNotNull($activity);
        $this->assertEquals('Deleted Opportunity', $activity->description);
    }
} 