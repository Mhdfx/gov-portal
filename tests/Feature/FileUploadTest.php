<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_upload_valid_pdf_file()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $file]
        ));

        $response->assertStatus(200);
        Storage::disk('public')->assertExists('auto-entrepreneur/documents/' . $file->hashName());
    }

    /** @test */
    public function user_can_upload_valid_image_file()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('identity.jpg', 600, 400);

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $file]
        ));

        $response->assertStatus(200);
        Storage::disk('public')->assertExists('auto-entrepreneur/documents/' . $file->hashName());
    }

    /** @test */
    public function file_upload_rejects_invalid_file_type()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.exe', 1000, 'application/x-msdownload');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $file]
        ));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['identity_document']);
    }

    /** @test */
    public function file_upload_rejects_file_exceeding_size_limit()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Create a file larger than 10MB (10240 KB)
        $file = UploadedFile::fake()->create('large-document.pdf', 11000, 'application/pdf');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $file]
        ));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['identity_document']);
    }

    /** @test */
    public function file_upload_accepts_valid_file_within_size_limit()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Create a file just under 10MB
        $file = UploadedFile::fake()->create('document.pdf', 10000, 'application/pdf');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $file]
        ));

        $response->assertStatus(200);
    }

    /** @test */
    public function multiple_file_uploads_work_correctly()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $identityFile = UploadedFile::fake()->create('identity.pdf', 1000, 'application/pdf');
        $businessPlan = UploadedFile::fake()->create('business-plan.pdf', 2000, 'application/pdf');
        $cv = UploadedFile::fake()->create('cv.pdf', 1500, 'application/pdf');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            [
                'identity_document' => $identityFile,
                'business_plan' => $businessPlan,
                'cv' => $cv,
            ]
        ));

        $response->assertStatus(200);
        Storage::disk('public')->assertExists('auto-entrepreneur/documents/' . $identityFile->hashName());
        Storage::disk('public')->assertExists('auto-entrepreneur/business-plans/' . $businessPlan->hashName());
        Storage::disk('public')->assertExists('auto-entrepreneur/cv/' . $cv->hashName());
    }

    /** @test */
    public function file_upload_sanitizes_file_names()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // File name with special characters
        $file = UploadedFile::fake()->create('file with spaces & special chars!.pdf', 1000, 'application/pdf');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            ['identity_document' => $file]
        ));

        $response->assertStatus(200);
        // File should be stored with sanitized name
        $this->assertTrue(Storage::disk('public')->exists('auto-entrepreneur/documents/' . $file->hashName()));
    }

    /** @test */
    public function optional_file_uploads_are_not_required()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $identityFile = UploadedFile::fake()->create('identity.pdf', 1000, 'application/pdf');

        $response = $this->post(route('forms.auto-entrepreneur.submit'), array_merge(
            $this->getAutoEntrepreneurFormData(),
            [
                'identity_document' => $identityFile,
                // business_plan, cv, financial_projections are optional
            ]
        ));

        $response->assertStatus(200);
    }

    /**
     * Get valid auto-entrepreneur form data for testing
     */
    private function getAutoEntrepreneurFormData(): array
    {
        return [
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














