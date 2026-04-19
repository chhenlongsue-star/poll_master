<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Check if a user with this google_id already exists
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // 2. If not, check if a user with this email exists (to link accounts)
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Update existing user with google_id
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // 3. Create a brand new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make(Str::random(24)), // Random password for security
                        'role' => 'user', 
                    ]);
                }
            }

            Auth::login($user);

            return redirect()->intended('dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google sign-in failed. Please try again.');
        }
    }
}