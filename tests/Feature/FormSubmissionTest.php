<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_submit_investment_form()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'postal_code' => '20000',
            'project_name' => $this->faker->company,
            'project_description' => $this->faker->paragraph,
            'investment_amount' => 100000,
            'currency' => 'MAD',
            'investment_type' => 'equity',
            'sector' => 'Technology',
            'investment_purpose' => $this->faker->paragraph,
            'business_stage' => 'startup',
            'target_market' => $this->faker->paragraph,
            'motivation' => $this->faker->paragraph,
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];

        $response = $this->postJson(route('forms.investment.submit'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('investment_submissions', [
            'user_id' => $user->id,
            'contact_email' => $data['email'],
        ]);
    }

    /** @test */
    public function investment_form_requires_all_required_fields()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->postJson(route('forms.investment.submit'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone']);
    }

    /** @test */
    public function user_can_submit_project_carrier_form()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'postal_code' => '20000',
            'project_name' => $this->faker->company,
            'project_description' => $this->faker->paragraph,
            'sector' => 'Technology',
            'development_stage' => 'prototype',
            'project_type' => 'startup',
            'target_market' => $this->faker->paragraph,
            'team_size' => 5,
            'funding_required' => 200000,
            'funding_currency' => 'MAD',
            'funding_purpose' => $this->faker->paragraph,
            'location_region' => $this->faker->state,
            'location_city' => $this->faker->city,
            'motivation' => $this->faker->paragraph,
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];

        $response = $this->postJson(route('forms.project-carrier.submit'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('project_carrier_submissions', [
            'user_id' => $user->id,
            'project_name' => $data['project_name'],
        ]);
    }

    /** @test */
    public function user_can_submit_auto_entrepreneur_form()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('identity.pdf', 1000, 'application/pdf');

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'postal_code' => '20000',
            'business_name' => $this->faker->company,
            'business_description' => $this->faker->paragraph,
            'business_type' => 'service',
            'business_sector' => 'Services',
            'start_date' => now()->addDays(30)->format('Y-m-d'),
            'expected_monthly_revenue' => 50000,
            'business_address' => $this->faker->address,
            'business_city' => $this->faker->city,
            'business_region' => $this->faker->state,
            'has_legal_status' => true,
            'legal_status_type' => 'auto_entrepreneur',
            'initial_investment' => 50000,
            'funding_source' => 'personal_savings',
            'monthly_expenses' => 15000,
            'has_bank_account' => true,
            'bank_name' => 'Test Bank',
            'identity_document' => $file,
            'target_market' => $this->faker->paragraph,
            'motivation' => $this->faker->paragraph,
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];

        $response = $this->postJson(route('forms.auto-entrepreneur.submit'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('auto_entrepreneur_submissions', [
            'user_id' => $user->id,
            'business_name' => $data['business_name'],
        ]);
    }

    /** @test */
    public function auto_entrepreneur_form_requires_identity_document()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'postal_code' => '20000',
            'business_name' => $this->faker->company,
            'business_description' => $this->faker->paragraph,
            'business_type' => 'service',
            'business_sector' => 'Services',
            'start_date' => now()->addDays(30)->format('Y-m-d'),
            'expected_monthly_revenue' => 50000,
            'business_address' => $this->faker->address,
            'business_city' => $this->faker->city,
            'business_region' => $this->faker->state,
            'has_legal_status' => false,
            'initial_investment' => 50000,
            'funding_source' => 'personal_savings',
            'monthly_expenses' => 15000,
            'has_bank_account' => false,
            'target_market' => $this->faker->paragraph,
            'motivation' => $this->faker->paragraph,
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];

        $response = $this->postJson(route('forms.auto-entrepreneur.submit'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['identity_document']);
    }
}














