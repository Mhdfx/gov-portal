<?php

namespace Database\Factories;

use App\Models\InvestmentSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvestmentSubmission>
 */
class InvestmentSubmissionFactory extends Factory
{
    protected $model = InvestmentSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_name' => $this->faker->company . ' Project',
            'project_description' => $this->faker->paragraph(3),
            'investment_amount' => $this->faker->numberBetween(10000, 1000000),
            'currency' => $this->faker->randomElement(['MAD', 'EUR', 'USD']),
            'investment_type' => $this->faker->randomElement(['equity', 'loan', 'grant']),
            'sector' => $this->faker->randomElement(['Technology', 'Agriculture', 'Tourism', 'Manufacturing', 'Services']),
            'region' => $this->faker->randomElement(['Casablanca-Settat', 'Rabat-Salé-Kénitra', 'Tanger-Tétouan-Al Hoceïma']),
            'city' => $this->faker->city,
            'contact_person' => $this->faker->name,
            'contact_email' => $this->faker->email,
            'contact_phone' => '+2126' . $this->faker->numerify('#########'),
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'completed']),
            'submitted_at' => now(),
        ];
    }
}

