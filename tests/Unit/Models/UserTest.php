<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_role_helper_method()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('user'));
    }

    /** @test */
    public function user_has_is_admin_helper_method()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sectoralAdmin = User::factory()->create(['role' => 'sectoral_admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($sectoralAdmin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function user_has_is_verified_helper_method()
    {
        $verifiedUser = User::factory()->create(['verification_status' => 'verified']);
        $pendingUser = User::factory()->create(['verification_status' => 'pending']);

        $this->assertTrue($verifiedUser->isVerified());
        $this->assertFalse($pendingUser->isVerified());
    }

    /** @test */
    public function user_can_have_profile()
    {
        $user = User::factory()->create();
        $profile = $user->profile()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $this->assertNotNull($user->profile);
        $this->assertEquals('Test', $user->profile->first_name);
    }

    /** @test */
    public function user_can_have_submissions()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->investmentSubmissions());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->projectCarrierSubmissions());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->autoEntrepreneurSubmissions());
    }
}














