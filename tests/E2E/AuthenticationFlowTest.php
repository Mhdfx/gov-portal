<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * End-to-End Authentication Flow Tests
 */
class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_and_login()
    {
        // Register
        $registrationData = [
            'role' => 'user',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'phone' => '+212612345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => '123 Test St',
            'city' => 'Casablanca',
            'region' => 'Casablanca-Settat',
        ];

        $response = $this->post('/register', $registrationData);
        $response->assertRedirect();

        // Verify user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'testuser'
        ]);

        // Login
        $loginResponse = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $loginResponse->assertRedirect();
    }

    /** @test */
    public function user_with_2fa_must_verify_code()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_enabled' => true,
            'two_factor_secret' => 'TEST_SECRET'
        ]);

        // Attempt login
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password'
        ]);

        // Should redirect to 2FA verification
        $response->assertRedirect();
        $this->assertTrue(session()->has('2fa_required'));
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->get('/user/dashboard');
        $response->assertRedirect('/login');
    }
}














