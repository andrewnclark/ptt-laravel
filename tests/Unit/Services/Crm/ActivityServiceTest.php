<?php

namespace Tests\Unit\Services\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Opportunity;
use App\Models\User;
use App\Services\Crm\ActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ActivityService $activityService;
    protected User $user;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->activityService = new ActivityService();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create([
            'name' => 'Test Company',
            'status' => Company::STATUS_LEAD,
        ]);
        
        // Set the authenticated user for testing
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_record_an_activity_for_a_subject()
    {
        // Act
        $activity = $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_CREATED,
            'Test activity description',
            ['test' => 'data'],
            null,
            $this->user,
            true
        );

        // Assert
        $this->assertDatabaseHas('crm_activities', [
            'id' => $activity->id,
            'subject_type' => get_class($this->company),
            'subject_id' => $this->company->id,
            'user_id' => $this->user->id,
            'type' => Activity::TYPE_CREATED,
            'description' => 'Test activity description',
            'is_system_generated' => true,
        ]);
        
        $this->assertEquals(['test' => 'data'], $activity->properties);
    }

    /** @test */
    public function it_can_add_a_note_to_a_subject()
    {
        // Act
        $note = 'This is a test note';
        $activity = $this->activityService->addNote($this->company, $note, $this->user);

        // Assert
        $this->assertDatabaseHas('crm_activities', [
            'id' => $activity->id,
            'subject_type' => get_class($this->company),
            'subject_id' => $this->company->id,
            'user_id' => $this->user->id,
            'type' => Activity::TYPE_NOTE_ADDED,
            'description' => $note,
            'is_system_generated' => false,
        ]);
    }

    /** @test */
    public function it_can_get_activities_for_a_subject()
    {
        // Arrange
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_CREATED,
            'Created company',
            [],
            null,
            $this->user
        );
        
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_UPDATED,
            'Updated company',
            [],
            null,
            $this->user
        );
        
        $this->activityService->addNote(
            $this->company,
            'Note for company',
            $this->user
        );

        // Act
        $activities = $this->activityService->getActivitiesForSubject($this->company);

        // Assert
        $this->assertEquals(3, $activities->total());
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activities->first()->type);
        $this->assertEquals(Activity::TYPE_CREATED, $activities->last()->type);
    }

    /** @test */
    public function it_can_filter_activities_by_type()
    {
        // Arrange
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_CREATED,
            'Created company'
        );
        
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_UPDATED,
            'Updated company'
        );
        
        $this->activityService->addNote(
            $this->company,
            'Note for company'
        );

        // Act
        $activities = $this->activityService->getActivitiesForSubject(
            $this->company, 
            ['type' => Activity::TYPE_NOTE_ADDED]
        );

        // Assert
        $this->assertEquals(1, $activities->total());
        $this->assertEquals(Activity::TYPE_NOTE_ADDED, $activities->first()->type);
    }

    /** @test */
    public function it_can_get_recent_activities_across_the_system()
    {
        // Arrange
        $opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Test Opportunity',
        ]);
        
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_CREATED,
            'Created company'
        );
        
        $this->activityService->recordActivity(
            $opportunity,
            Activity::TYPE_CREATED,
            'Created opportunity'
        );

        // Act
        $activities = $this->activityService->getRecentActivities(10);

        // Assert
        $this->assertEquals(2, $activities->count());
        $this->assertEquals(Activity::TYPE_CREATED, $activities->first()->type);
        $this->assertEquals('Created opportunity', $activities->first()->description);
    }

    /** @test */
    public function it_can_get_activity_type_stats()
    {
        // Arrange
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_CREATED,
            'Created company'
        );
        
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_UPDATED,
            'Updated company'
        );
        
        $this->activityService->recordActivity(
            $this->company,
            Activity::TYPE_UPDATED,
            'Updated company again'
        );
        
        $this->activityService->addNote(
            $this->company,
            'Note for company'
        );

        // Act
        $stats = $this->activityService->getActivityTypeStats($this->company);

        // Assert
        $this->assertEquals(3, $stats->count());
        
        $createdStat = $stats->where('type', Activity::TYPE_CREATED)->first();
        $this->assertEquals(1, $createdStat->count);
        
        $updatedStat = $stats->where('type', Activity::TYPE_UPDATED)->first();
        $this->assertEquals(2, $updatedStat->count);
        
        $noteStat = $stats->where('type', Activity::TYPE_NOTE_ADDED)->first();
        $this->assertEquals(1, $noteStat->count);
    }
} 