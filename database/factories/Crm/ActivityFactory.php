<?php

namespace Database\Factories\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjectModel = Company::class;
        $subjectId = Company::factory()->create()->id;
        
        return [
            'user_id' => User::factory(),
            'subject_type' => $subjectModel,
            'subject_id' => $subjectId,
            'type' => $this->faker->randomElement([
                Activity::TYPE_CREATED,
                Activity::TYPE_UPDATED,
                Activity::TYPE_DELETED,
                Activity::TYPE_NOTE_ADDED,
                Activity::TYPE_CUSTOM,
            ]),
            'description' => $this->faker->sentence(),
            'properties' => ['data' => $this->faker->sentence()],
            'is_system_generated' => $this->faker->boolean(70),
        ];
    }

    /**
     * Configure the activity for a specific subject.
     *
     * @param mixed $subject
     * @return $this
     */
    public function forSubject($subject)
    {
        return $this->state(function (array $attributes) use ($subject) {
            return [
                'subject_type' => get_class($subject),
                'subject_id' => $subject->id,
            ];
        });
    }

    /**
     * Configure the activity with a specific type.
     *
     * @param string $type
     * @return $this
     */
    public function withType(string $type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }

    /**
     * Configure the activity as a note.
     *
     * @param string $note
     * @return $this
     */
    public function asNote(string $note = null)
    {
        return $this->state(function (array $attributes) use ($note) {
            return [
                'type' => Activity::TYPE_NOTE_ADDED,
                'description' => $note ?? $this->faker->paragraph(),
                'properties' => ['note' => $note ?? $this->faker->paragraph()],
                'is_system_generated' => false,
            ];
        });
    }
} 