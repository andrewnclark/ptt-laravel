<?php

declare(strict_types=1);

namespace Tests\Feature\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Task $task;
    protected Company $company;
    protected Contact $contact;
    protected Opportunity $opportunity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->contact = Contact::factory()->create([
            'company_id' => $this->company->id
        ]);
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
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_tasks()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_get_all_tasks()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'priority',
                        'due_date',
                        'assigned_to',
                    ]
                ]
            ]);
    }

    #[Test]
    public function authenticated_user_can_get_a_single_task()
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/tasks/{$this->task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->task->id,
                    'title' => $this->task->title,
                ]
            ]);
    }

    #[Test]
    public function authenticated_user_can_create_a_task()
    {
        $taskData = [
            'title' => 'New Test Task',
            'description' => 'This is a test task',
            'status' => Task::STATUS_NOT_STARTED,
            'priority' => Task::PRIORITY_HIGH,
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'assigned_to' => $this->user->id,
            'company_id' => $this->company->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Test Task',
                    'status' => Task::STATUS_NOT_STARTED,
                ]
            ]);

        $this->assertDatabaseHas('crm_tasks', [
            'title' => 'New Test Task',
            'company_id' => $this->company->id,
        ]);

        // Verify activity was recorded
        $taskId = $response->json('data.id');
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Task::class,
            'subject_id' => $taskId,
            'type' => Activity::TYPE_CREATED,
        ]);
    }

    #[Test]
    public function authenticated_user_can_complete_a_task()
    {
        // When
        $response = $this->actingAs($this->user)
            ->putJson("/api/tasks/{$this->task->id}/complete");

        // Then
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->task->id,
                    'status' => Task::STATUS_COMPLETED,
                ]
            ]);

        $this->task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $this->task->status);
        $this->assertNotNull($this->task->completed_at);

        // Verify activities were recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Task::class,
            'subject_id' => $this->task->id,
            'type' => Activity::TYPE_TASK_COMPLETED,
        ]);

        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_TASK_COMPLETED,
        ]);

        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Contact::class,
            'subject_id' => $this->contact->id,
            'type' => Activity::TYPE_TASK_COMPLETED,
        ]);

        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Opportunity::class,
            'subject_id' => $this->opportunity->id,
            'type' => Activity::TYPE_TASK_COMPLETED,
        ]);
    }

    #[Test]
    public function authenticated_user_can_update_a_task()
    {
        $updateData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated task description',
            'priority' => Task::PRIORITY_HIGH,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/tasks/{$this->task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->task->id,
                    'title' => 'Updated Task Title',
                    'priority' => Task::PRIORITY_HIGH,
                ]
            ]);

        $this->assertDatabaseHas('crm_tasks', [
            'id' => $this->task->id,
            'title' => 'Updated Task Title',
            'description' => 'Updated task description',
        ]);

        // Verify update activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Task::class,
            'subject_id' => $this->task->id,
            'type' => Activity::TYPE_UPDATED,
        ]);
    }

    #[Test]
    public function authenticated_user_can_delete_a_task()
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/tasks/{$this->task->id}");

        $response->assertStatus(200);

        // Task should be soft deleted
        $this->assertSoftDeleted('crm_tasks', [
            'id' => $this->task->id,
        ]);

        // Verify delete activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Task::class,
            'subject_id' => $this->task->id,
            'type' => Activity::TYPE_DELETED,
        ]);
    }
} 