<?php

namespace Database\Factories;

use App\Models\INDHSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\INDHSubmission>
 */
class INDHSubmissionFactory extends Factory
{
    protected $model = INDHSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'submission_number' => 'IND-' . strtoupper($this->faker->bothify('??####')),
            'project_title' => $this->faker->sentence(4),
            'project_description' => $this->faker->paragraph(3),
            'project_type' => $this->faker->randomElement(['social', 'economic', 'environmental', 'cultural', 'educational', 'health']),
            'community_impact' => $this->faker->paragraph(2),
            'target_beneficiaries' => $this->faker->numberBetween(10, 1000),
            'funding_required' => $this->faker->numberBetween(50000, 1000000),
            'funding_currency' => $this->faker->randomElement(['MAD', 'EUR', 'USD']),
            'project_duration_months' => $this->faker->numberBetween(1, 36),
            'location_region' => $this->faker->randomElement(['Casablanca-Settat', 'Rabat-Salé-Kénitra', 'Tanger-Tétouan-Al Hoceïma']),
            'location_city' => $this->faker->city,
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'in_progress']),
            'submitted_at' => now(),
        ];
    }
}

