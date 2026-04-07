<?php

namespace Database\Factories;

use App\Models\IdeaCarrierSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IdeaCarrierSubmission>
 */
class IdeaCarrierSubmissionFactory extends Factory
{
    protected $model = IdeaCarrierSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'submission_number' => 'IDE-' . strtoupper($this->faker->bothify('??####')),
            'idea_title' => $this->faker->sentence(4),
            'idea_description' => $this->faker->paragraph(3),
            'sector' => $this->faker->randomElement(['Technology', 'Agriculture', 'Tourism', 'Manufacturing', 'Services']),
            'development_level' => $this->faker->randomElement(['concept', 'research', 'prototype', 'testing', 'ready_for_development']),
            'support_needed' => $this->faker->paragraph(2),
            'budget_estimate' => $this->faker->numberBetween(10000, 500000),
            'budget_currency' => $this->faker->randomElement(['MAD', 'EUR', 'USD']),
            'location_region' => $this->faker->randomElement(['Casablanca-Settat', 'Rabat-Salé-Kénitra', 'Tanger-Tétouan-Al Hoceïma']),
            'location_city' => $this->faker->city,
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'in_progress']),
            'submitted_at' => now(),
        ];
    }
}

