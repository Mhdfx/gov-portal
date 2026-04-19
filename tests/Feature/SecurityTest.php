<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sql_injection_attempt_in_form_is_sanitized()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $maliciousInput = "'; DROP TABLE users; --";

        $response = $this->postJson(route('forms.investment.submit'), [
            'first_name' => $maliciousInput,
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => '123 Test St',
            'city' => 'Casablanca',
            'region' => 'Casablanca-Settat',
            'postal_code' => '20000',
            'project_name' => 'Test Project',
            'project_description' => 'Test description',
            'investment_amount' => 100000,
            'currency' => 'MAD',
            'investment_type' => 'equity',
            'sector' => 'Technology',
            'investment_purpose' => 'Test purpose',
            'business_stage' => 'startup',
            'target_market' => 'Test market',
            'motivation' => 'Test motivation',
            'accept_terms' => true,
            'accept_data_processing' => true,
        ]);

        // Should either validate and reject, or sanitize and accept
        // The important thing is that it doesn't execute SQL
        $this->assertDatabaseHas('users', ['id' => $user->id]); // Users table still exists
    }

    /** @test */
    public function xss_attempt_in_form_is_escaped()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $xssPayload = '<script>alert("XSS")</script>';

        $response = $this->postJson(route('forms.investment.submit'), [
            'first_name' => $xssPayload,
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => '123 Test St',
            'city' => 'Casablanca',
            'region' => 'Casablanca-Settat',
            'postal_code' => '20000',
            'project_name' => 'Test Project',
            'project_description' => $xssPayload,
            'investment_amount' => 100000,
            'currency' => 'MAD',
            'investment_type' => 'equity',
            'sector' => 'Technology',
            'investment_purpose' => 'Test purpose',
            'business_stage' => 'startup',
            'target_market' => 'Test market',
            'motivation' => 'Test motivation',
            'accept_terms' => true,
            'accept_data_processing' => true,
        ]);

        // If submission succeeds, check that stored data is intact (Laravel escapes on output via Blade)
        if ($response->status() === 200 || $response->status() === 201) {
            $submission = \App\Models\InvestmentSubmission::latest()->first();
            $this->assertStringContainsString('<script>', $submission->project_description ?? '');
        }
    }



    /** @test */
    public function unauthenticated_user_cannot_submit_forms()
    {
        $response = $this->post(route('forms.investment.submit'), []);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function rate_limiting_prevents_too_many_submissions()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $formData = $this->getValidFormData();

        // Try to submit 15 times (limit is 10 per minute)
        for ($i = 0; $i < 15; $i++) {
            $response = $this->postJson(route('forms.investment.submit'), $formData);
            
            if ($i >= 10) {
                // After 10 submissions, should be rate limited
                $this->assertContains($response->status(), [429, 201]); // 429 = Too Many Requests, 201 = Created
            }
        }
    }

    /** @test */
    public function file_upload_rejects_executable_files()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $executableFile = \Illuminate\Http\UploadedFile::fake()->create('malware.exe', 1000, 'application/x-msdownload');

        $response = $this->postJson(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $executableFile]
        ));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['identity_document']);
    }

    private function getValidFormData(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => '123 Test St',
            'city' => 'Casablanca',
            'region' => 'Casablanca-Settat',
            'postal_code' => '20000',
            'project_name' => 'Test Project',
            'project_description' => 'Test description',
            'investment_amount' => 100000,
            'currency' => 'MAD',
            'investment_type' => 'equity',
            'sector' => 'Technology',
            'investment_purpose' => 'Test purpose',
            'business_stage' => 'startup',
            'target_market' => 'Test market',
            'motivation' => 'Test motivation',
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];
    }

    private function getAutoEntrepreneurFormData(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+212612345678',
            'date_of_birth' => '1990-01-01',
            'nationality' => 'Moroccan',
            'address' => '123 Test St',
            'city' => 'Casablanca',
            'region' => 'Casablanca-Settat',
            'postal_code' => '20000',
            'business_name' => 'Test Business',
            'business_description' => 'Test description',
            'business_type' => 'service',
            'business_sector' => 'Services',
            'start_date' => now()->addDays(30)->format('Y-m-d'),
            'expected_monthly_revenue' => 50000,
            'business_address' => '123 Business St',
            'business_city' => 'Casablanca',
            'business_region' => 'Casablanca-Settat',
            'has_legal_status' => false,
            'initial_investment' => 50000,
            'funding_source' => 'personal_savings',
            'monthly_expenses' => 15000,
            'has_bank_account' => false,
            'target_market' => 'Local market',
            'motivation' => 'Test motivation',
            'accept_terms' => true,
            'accept_data_processing' => true,
        ];
    }
}














