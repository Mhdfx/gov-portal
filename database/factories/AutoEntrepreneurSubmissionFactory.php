<?php

namespace Database\Factories;

use App\Models\AutoEntrepreneurSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AutoEntrepreneurSubmission>
 */
class AutoEntrepreneurSubmissionFactory extends Factory
{
    protected $model = AutoEntrepreneurSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'submission_number' => 'AUT-' . strtoupper($this->faker->bothify('??####')),
            'business_name' => $this->faker->company,
            'business_description' => $this->faker->paragraph(3),
            'sector' => $this->faker->randomElement(['Technology', 'Agriculture', 'Tourism', 'Manufacturing', 'Services']),
            'business_type' => $this->faker->randomElement(['service', 'commerce', 'manufacturing', 'consulting', 'digital', 'other']),
            'startup_capital' => $this->faker->numberBetween(10000, 200000),
            'capital_currency' => $this->faker->randomElement(['MAD', 'EUR', 'USD']),
            'location_region' => $this->faker->randomElement(['Casablanca-Settat', 'Rabat-Salé-Kénitra', 'Tanger-Tétouan-Al Hoceïma']),
            'location_city' => $this->faker->city,
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'in_progress']),
            'submitted_at' => now(),
        ];
    }
}

