<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    protected $google2fa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->google2fa = new Google2FA();
    }

    /** @test */
    public function user_can_view_2fa_setup_page()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($user)->get('/2fa/setup');

        $response->assertStatus(200);
        $response->assertSee('Enable Two-Factor Authentication');
        $response->assertSee('QR code');
    }

    /** @test */
    public function user_can_enable_2fa_with_valid_code()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_secret' => $this->google2fa->generateSecretKey()
        ]);

        $code = $this->google2fa->getCurrentOtp($user->two_factor_secret);

        $response = $this->actingAs($user)->post('/2fa/enable', [
            'code' => $code
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->two_factor_enabled);
    }

    /** @test */
    public function user_cannot_enable_2fa_with_invalid_code()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_secret' => $this->google2fa->generateSecretKey()
        ]);

        $response = $this->actingAs($user)->post('/2fa/enable', [
            'code' => '000000'
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->two_factor_enabled);
    }

    /** @test */
    public function user_with_2fa_is_redirected_to_verification_after_login()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_enabled' => true,
            'two_factor_secret' => $this->google2fa->generateSecretKey(),
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password123'
        ]);

        $response->assertRedirect('/2fa/verify');
        $this->assertTrue(session()->has('2fa_required'));
    }

    /** @test */
    public function user_can_verify_2fa_code_and_complete_login()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_enabled' => true,
            'two_factor_secret' => $this->google2fa->generateSecretKey(),
            'password' => Hash::make('password123')
        ]);

        // Simulate login attempt
        $this->post('/login', [
            'username' => $user->username,
            'password' => 'password123'
        ]);

        // Verify 2FA code
        $code = $this->google2fa->getCurrentOtp($user->two_factor_secret);

        $response = $this->post('/2fa/verify', [
            'code' => $code
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_can_use_recovery_code()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_enabled' => true,
            'two_factor_secret' => $this->google2fa->generateSecretKey(),
            'recovery_codes' => json_encode(['RECOVERY123', 'RECOVERY456']),
            'password' => Hash::make('password123')
        ]);

        // Simulate login attempt
        $this->post('/login', [
            'username' => $user->username,
            'password' => 'password123'
        ]);

        // Use recovery code
        $response = $this->post('/2fa/verify', [
            'code' => 'RECOVERY123'
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
        
        // Recovery code should be removed
        $recoveryCodes = json_decode($user->fresh()->recovery_codes, true);
        $this->assertNotContains('RECOVERY123', $recoveryCodes);
    }

    /** @test */
    public function user_can_disable_2fa()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'verification_status' => 'verified',
            'two_factor_enabled' => true,
            'two_factor_secret' => $this->google2fa->generateSecretKey(),
            'password' => Hash::make('password123')
        ]);

        $code = $this->google2fa->getCurrentOtp($user->two_factor_secret);

        $response = $this->actingAs($user)->post('/2fa/disable', [
            'password' => 'password123',
            'code' => $code
        ]);

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->two_factor_enabled);
        $this->assertNull($user->fresh()->two_factor_secret);
    }
}














