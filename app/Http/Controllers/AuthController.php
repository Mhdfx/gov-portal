<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Constants\AppConstants;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }
        
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Invalid credentials']);
        }

        if ($user->verification_status !== AppConstants::VERIFICATION_VERIFIED) {
            return back()->withErrors(['username' => 'Your account is not verified']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials']);
        }

        // Check if 2FA is enabled
        if ($user->two_factor_enabled && $user->two_factor_secret) {
            // Store user ID in session for 2FA verification
            $request->session()->put('2fa_user_id', $user->id);
            $request->session()->put('2fa_required', true);
            
            // Redirect to 2FA verification
            return redirect()->route('2fa.verify');
        }
        
        // Log the user in
        Auth::login($user, $request->filled('remember'));
        
        // Update last login
        $user->update(['last_login_at' => now()]);

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to intended URL (if user was trying to access a specific page) or dashboard
        $dashboardUrl = route($this->getDashboardRoute($user));
        return redirect()->intended($dashboardUrl);
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }
        
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'role' => 'required|in:user,company',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'username' => 'required|string|unique:users,username|max:255|alpha_dash',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
        ]);

        // Create user
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'region' => $validated['region'],
            'city' => $validated['city'],
            'verification_status' => AppConstants::VERIFICATION_PENDING, // New users need verification
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'region' => $validated['region'],
            'country' => 'Morocco',
            'profile_type' => $validated['role'] === 'company' ? 'company' : 'individual',
        ]);

        // Log the user in
        Auth::login($user);

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to appropriate dashboard
        return $this->redirectToDashboard($user)
            ->with('success', 'Your account has been created successfully! Please wait for verification to access all features.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Get dashboard route name for user
     */
    private function getDashboardRoute($user)
    {
        return match($user->role) {
            AppConstants::ROLE_MAIN_ADMIN => 'admin.dashboard',
            AppConstants::ROLE_INSTITUTIONAL_ADMIN => 'institutional.dashboard',
            AppConstants::ROLE_SECTORAL_ADMIN => 'sectoral.dashboard',
            AppConstants::ROLE_COMPANY => 'company.dashboard',
            'candidate' => 'candidate.dashboard',
            default => 'user.dashboard',
        };
    }

    /**
     * Redirect to appropriate dashboard
     */
    private function redirectToDashboard($user)
    {
        return redirect()->route($this->getDashboardRoute($user));
    }
}



























