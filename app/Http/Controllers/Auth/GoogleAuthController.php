<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        // stateless() is critical for cloud hosting like Render to avoid session mismatches
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Search for user by google_id OR email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Update user in case they didn't have a google_id before
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            } else {
                // Create the user if they don't exist
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)), 
                    'role' => 'user', 
                ]);
            }

            // This is the most important part!
            Auth::login($user, true);

            // Force a session save before redirecting
            request()->session()->regenerate();

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            // This will help you see the error if it fails again
            return redirect('/login')->with('error', 'Google sign-in failed: ' . $e->getMessage());
        }
    }
}