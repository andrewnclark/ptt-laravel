<?php

namespace Database\Factories\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement([
                Task::STATUS_NOT_STARTED,
                Task::STATUS_IN_PROGRESS,
                Task::STATUS_COMPLETED,
            ]),
            'priority' => $this->faker->randomElement([
                Task::PRIORITY_LOW,
                Task::PRIORITY_MEDIUM,
                Task::PRIORITY_HIGH,
            ]),
            'due_date' => $this->faker->dateTimeBetween('-1 week', '+2 weeks'),
            'completed_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 week', 'now'),
            'assigned_to' => User::factory(),
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'contact_id' => Contact::factory(),
            'opportunity_id' => Opportunity::factory(),
        ];
    }

    /**
     * Configure the task as pending.
     *
     * @return $this
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Task::STATUS_NOT_STARTED,
                'completed_at' => null,
            ];
        });
    }

    /**
     * Configure the task as completed.
     *
     * @return $this
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Task::STATUS_COMPLETED,
                'completed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            ];
        });
    }
} 