<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleOAuthController extends Controller
{
    public function __construct(
        protected SocialUserService $socialUserService
    ) {}

    /**
     * Redirect to Google OAuth provider
     */
    public function redirect(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = $this->socialUserService->findOrCreateFromGoogle($googleUser);
            
            Auth::login($user, true);
            
            return redirect()->intended(route('home', absolute: false));
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }
}
