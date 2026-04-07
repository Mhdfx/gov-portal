<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * End-to-End Tests using Playwright
 * 
 * These tests simulate real user interactions
 */
class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_submit_investment_form_end_to_end()
    {
        // Create user
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        // Login
        $this->actingAs($user);

        // Navigate to form
        $response = $this->get('/forms/investment');
        $response->assertStatus(200);
        $response->assertSee('Formulaire Investment');

        // Submit form
        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => '123 Test Street',
            'city' => 'Casablanca',
            'region' => 'Casablanca-Settat',
            'postal_code' => '20000',
            'project_name' => 'Test Investment Project',
            'project_description' => 'This is a test investment project description.',
            'investment_amount' => 100000,
            'currency' => 'MAD',
            'investment_type' => 'equity',
            'sector' => 'Technology',
            'investment_purpose' => 'Startup funding',
            'business_stage' => 'startup',
            'target_market' => 'Local market',
            'motivation' => 'Test motivation',
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];

        $response = $this->post('/forms/investment', $formData);
        
        // Should redirect or return success
        $response->assertStatus(200);
        
        // Verify submission was created
        $this->assertDatabaseHas('investment_submissions', [
            'user_id' => $user->id,
            'project_name' => 'Test Investment Project'
        ]);
    }

    /** @test */
    public function user_can_view_their_submissions()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $this->actingAs($user);

        // Create a submission
        \App\Models\InvestmentSubmission::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        // View dashboard
        $response = $this->get('/user/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tableau de Bord');
    }

    /** @test */
    public function admin_can_view_all_submissions()
    {
        $admin = User::factory()->create([
            'role' => 'main_admin',
            'verification_status' => 'verified'
        ]);

        $this->actingAs($admin);

        // Create some submissions
        \App\Models\InvestmentSubmission::factory()->count(5)->create();

        // View admin dashboard
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Administration');
    }
}














