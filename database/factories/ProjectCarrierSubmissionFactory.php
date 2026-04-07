<?php

namespace Database\Factories;

use App\Models\ProjectCarrierSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectCarrierSubmission>
 */
class ProjectCarrierSubmissionFactory extends Factory
{
    protected $model = ProjectCarrierSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'submission_number' => 'PRJ-' . strtoupper($this->faker->bothify('??####')),
            'project_name' => $this->faker->company . ' Project',
            'project_description' => $this->faker->paragraph(3),
            'sector' => $this->faker->randomElement(['Technology', 'Agriculture', 'Tourism', 'Manufacturing', 'Services']),
            'development_stage' => $this->faker->randomElement(['idea', 'prototype', 'mvp', 'scaling', 'established']),
            'team_size' => $this->faker->numberBetween(1, 50),
            'funding_required' => $this->faker->numberBetween(50000, 500000),
            'funding_currency' => $this->faker->randomElement(['MAD', 'EUR', 'USD']),
            'location_region' => $this->faker->randomElement(['Casablanca-Settat', 'Rabat-Salé-Kénitra', 'Tanger-Tétouan-Al Hoceïma']),
            'location_city' => $this->faker->city,
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'in_progress']),
            'submitted_at' => now(),
        ];
    }
}

