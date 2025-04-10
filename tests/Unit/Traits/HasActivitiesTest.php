<?php

namespace Tests\Unit\Traits;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\User;
use App\Traits\HasActivities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasActivitiesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create([
            'name' => 'Test Company',
            'status' => Company::STATUS_LEAD,
        ]);
        
        // Set the authenticated user for testing
        $this->actingAs($this->user);
    }

    /** @test */
    public function model_has_activities_relationship()
    {
        $this->assertInstanceOf(MorphMany::class, $this->company->activities());
    }

    /** @test */
    public function it_can_record_activity()
    {
        // Act
        $activity = $this->company->recordActivity(
            Activity::TYPE_CUSTOM,
            'Test activity',
            ['foo' => 'bar'],
            true
        );

        // Assert
        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertDatabaseHas('crm_activities', [
            'id' => $activity->id,
            'subject_type' => get_class($this->company),
            'subject_id' => $this->company->id,
            'user_id' => $this->user->id,
            'type' => Activity::TYPE_CUSTOM,
            'description' => 'Test activity',
            'is_system_generated' => true,
        ]);
        
        $this->assertEquals(['foo' => 'bar'], $activity->properties);
    }

    /** @test */
    public function it_can_add_a_note()
    {
        // Act
        $note = 'This is a test note';
        $activity = $this->company->addNote($note);

        // Assert
        $this->assertInstanceOf(Activity::class, $activity);
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
    public function it_can_get_recent_activities()
    {
        // Arrange
        for ($i = 0; $i < 5; $i++) {
            $this->company->recordActivity(
                Activity::TYPE_CUSTOM,
                "Activity {$i}",
                [],
                true
            );
        }

        // Act
        $recentActivities = $this->company->getRecentActivities(3);

        // Assert
        $this->assertEquals(3, $recentActivities->count());
        $this->assertEquals("Activity 4", $recentActivities->first()->description);
        $this->assertEquals("Activity 2", $recentActivities->last()->description);
    }

    /** @test */
    public function it_records_activity_when_model_is_created()
    {
        // Creating a new company should trigger the created event
        // which should record an activity
        $newCompany = Company::create([
            'name' => 'New Test Company',
            'status' => Company::STATUS_LEAD,
        ]);

        // Assert
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => get_class($newCompany),
            'subject_id' => $newCompany->id,
            'type' => Activity::TYPE_CREATED,
        ]);
    }

    /** @test */
    public function it_records_activity_when_model_is_updated()
    {
        // Act
        $this->company->name = 'Updated Company Name';
        $this->company->save();

        // Assert
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => get_class($this->company),
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_UPDATED,
        ]);
        
        // Check the properties contain the change details
        $activity = Activity::where('subject_type', get_class($this->company))
            ->where('subject_id', $this->company->id)
            ->where('type', Activity::TYPE_UPDATED)
            ->first();
            
        $this->assertArrayHasKey('old', $activity->properties);
        $this->assertArrayHasKey('new', $activity->properties);
        $this->assertEquals('Test Company', $activity->properties['old']['name']);
        $this->assertEquals('Updated Company Name', $activity->properties['new']['name']);
    }

    /** @test */
    public function it_records_activity_when_model_is_deleted()
    {
        // Act
        $companyId = $this->company->id;
        $this->company->delete();

        // Assert
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => get_class($this->company),
            'subject_id' => $companyId,
            'type' => Activity::TYPE_DELETED,
        ]);
    }

    /** @test */
    public function it_only_records_update_activity_when_attributes_change()
    {
        // Act - Save without changes
        $this->company->save();
        
        // Assert - No activity should be recorded
        $this->assertDatabaseMissing('crm_activities', [
            'subject_type' => get_class($this->company),
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_UPDATED,
        ]);
        
        // Act - Save with changes
        $this->company->status = Company::STATUS_PROSPECT;
        $this->company->save();
        
        // Assert - Activity should be recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => get_class($this->company),
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_UPDATED,
        ]);
    }
} 