<?php

namespace Database\Factories;

use App\Models\TrainingSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSubmission>
 */
class TrainingSubmissionFactory extends Factory
{
    protected $model = TrainingSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'submission_number' => 'TRN-' . strtoupper($this->faker->bothify('??####')),
            'training_title' => $this->faker->sentence(4),
            'training_description' => $this->faker->paragraph(3),
            'training_type' => $this->faker->randomElement(['technical', 'business', 'soft_skills', 'certification', 'workshop', 'seminar']),
            'target_audience' => $this->faker->paragraph(2),
            'participant_count' => $this->faker->numberBetween(10, 100),
            'duration_hours' => $this->faker->numberBetween(8, 120),
            'preferred_location' => $this->faker->city,
            'preferred_schedule' => $this->faker->paragraph(1),
            'budget_available' => $this->faker->numberBetween(10000, 200000),
            'budget_currency' => $this->faker->randomElement(['MAD', 'EUR', 'USD']),
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'scheduled', 'completed']),
            'submitted_at' => now(),
        ];
    }
}

