<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

class TwoFactorAuthController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show 2FA setup page
     */
    public function showSetup()
    {
        $user = Auth::user();
        
        // Generate secret if not exists
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }
        
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );
        
        return view('auth.2fa.setup', [
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $user->two_factor_secret,
            'recoveryCodes' => $this->getRecoveryCodes($user)
        ]);
    }

    /**
     * Enable 2FA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        
        // Verify the code
        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code,
            2 // 2-step tolerance
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes($user);
        
        // Enable 2FA
        $user->two_factor_enabled = true;
        $user->save();

        return redirect()->route('user.dashboard')
            ->with('success', 'Two-factor authentication enabled successfully!')
            ->with('recovery_codes', $recoveryCodes);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        // Verify 2FA code
        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code,
            2
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }

        // Disable 2FA
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->recovery_codes = null;
        $user->save();

        return redirect()->route('user.dashboard')
            ->with('success', 'Two-factor authentication disabled successfully!');
    }

    /**
     * Verify 2FA code during login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:6|max:16'
        ]);

        $userId = $request->session()->get('2fa_user_id');
        
        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['code' => 'Session expired. Please login again.']);
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['code' => 'User not found.']);
        }

        // Check recovery code first
        $recoveryCodes = json_decode($user->recovery_codes ?? '[]', true);
        $isRecoveryCode = in_array($request->code, $recoveryCodes);

        if ($isRecoveryCode) {
            // Remove used recovery code
            $recoveryCodes = array_values(array_diff($recoveryCodes, [$request->code]));
            $user->recovery_codes = json_encode($recoveryCodes);
            $user->save();
        } else {
            // Verify TOTP code
            $valid = $this->google2fa->verifyKey(
                $user->two_factor_secret,
                $request->code,
                2
            );

            if (!$valid) {
                return back()->withErrors(['code' => 'Invalid verification code']);
            }
        }

        // Clear 2FA session
        $request->session()->forget('2fa_user_id');
        $request->session()->forget('2fa_required');

        // Log the user in
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'));
    }

    /**
     * Show 2FA verification page
     */
    public function showVerification()
    {
        if (!session('2fa_required')) {
            return redirect()->route('login');
        }

        return view('auth.2fa.verify');
    }

    /**
     * Generate recovery codes
     */
    private function generateRecoveryCodes($user)
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = Str::random(10);
        }

        $user->recovery_codes = json_encode($codes);
        $user->save();

        return $codes;
    }

    /**
     * Get recovery codes
     */
    private function getRecoveryCodes($user)
    {
        if ($user->recovery_codes) {
            return json_decode($user->recovery_codes, true);
        }
        return [];
    }
}














