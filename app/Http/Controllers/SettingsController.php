<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SettingsController extends Controller
{
    /**
     * Toggle dark mode
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleDarkMode(Request $request)
    {
        $darkMode = $request->get('enabled', false);
        
        $cookie = Cookie::make('dark_mode', $darkMode ? 'true' : 'false', 60 * 24 * 365); // 1 year
        
        return response()->json([
            'success' => true,
            'dark_mode' => $darkMode
        ])->cookie($cookie);
    }

    /**
     * Save user preferences
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'dark_mode' => 'boolean',
            'language' => 'string|in:fr,ar,en',
            'notifications_email' => 'boolean',
            'notifications_push' => 'boolean',
            'timezone' => 'string|max:50'
        ]);
        
        // Save to user profile or preferences table
        if ($user->profile) {
            $user->profile->update([
                'preferences' => array_merge(
                    $user->profile->preferences ?? [],
                    $validated
                )
            ]);
        }
        
        // Set cookies for immediate effect
        $response = response()->json([
            'success' => true,
            'preferences' => $validated
        ]);
        
        if (isset($validated['dark_mode'])) {
            $response->cookie('dark_mode', $validated['dark_mode'] ? 'true' : 'false', 60 * 24 * 365);
        }
        
        if (isset($validated['language'])) {
            $response->cookie('language', $validated['language'], 60 * 24 * 365);
        }
        
        return $response;
    }

    /**
     * Get user preferences
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferences()
    {
        $user = Auth::user();
        
        $preferences = [
            'dark_mode' => request()->cookie('dark_mode', 'false') === 'true',
            'language' => request()->cookie('language', config('app.locale', 'fr')),
            'notifications_email' => true,
            'notifications_push' => false,
            'timezone' => config('app.timezone', 'Africa/Casablanca')
        ];
        
        if ($user->profile && isset($user->profile->preferences)) {
            $preferences = array_merge($preferences, $user->profile->preferences);
        }
        
        return response()->json([
            'success' => true,
            'preferences' => $preferences
        ]);
    }
}














