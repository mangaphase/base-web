<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialUserService
{
    /**
     * Find or create user from Google OAuth data
     */
    public function findOrCreateFromGoogle(SocialiteUser $googleUser): User
    {
        // Check if user already exists by google_id
        $existingUser = User::where('google_id', $googleUser->getId())->first();
        
        if ($existingUser) {
            return $existingUser;
        }

        // Check if user exists by email
        $userByEmail = User::where('email', $googleUser->getEmail())->first();
        
        if ($userByEmail) {
            // Update existing user with google_id
            $userByEmail->update([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(), // Google already verified the email
            ]);
            
            return $userByEmail;
        }

        // Create new user
        return $this->createUserFromGoogle($googleUser);
    }

    /**
     * Create new user from Google data
     */
    protected function createUserFromGoogle(SocialiteUser $googleUser): User
    {
        return User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'email_verified_at' => now(), // Google already verified the email
            'password' => Hash::make(Str::random(32)), // Random password since they login via Google
        ]);
    }
}
