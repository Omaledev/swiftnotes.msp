<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;


class AuthController extends Controller
{
    // showing the registeration form on the web
    public function showregister()
    {
        return view('auth.register');
    }

    // handling registration
    public function register(Request $request) {

        // register validation
        $request->validate([
         'name' => 'required|string|max: 255',
         'email' => 'required|email|unique:users,email',
         'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
        'name'=> $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'You have successfully logged login!');

    }

    // showing the login form on the web
    public function showlogin()
    {
        return view('auth.login');
    }
    //    handling login
    public function login (request $request) {

        // login validation
        $request->validate([
         'email' => 'required|email',
         'password' => 'required'
        ]);

        if(auth::attempt($request->only(['email','password']), $request->remember)) {
           $request->session()->regenerate();


           // Get the authenticated user with the created teams
             $user = Auth::user();

              // Check if user has owned teams
             if ($user->createdTeams->count() > 0) {
            $request->session()->put('current_team', $user->createdTeams->first());
          }

          return redirect()->intended('dashboard')->with('login_success', 'You have successfully logged in!');
        }

        return back()
        ->withErrors([
            'email' => 'Invalid credentials',
            'password' => 'Invalid credentials'
        ]);
    }

    // handling logout
    public function logout(request $request) {
     auth::logout();

     $request->session()->invalidate();
     $request->session()->regenerateToken();

     return redirect()->route('login')
     ->with('success', 'Logged out successfully');
    }

    // Redirect to Google for authentication
    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // Handle Google callback
    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();

    //         $user = User::where('email', $googleUser->getEmail())->first();

    //         if (!$user) {
    //             // Registration case
    //             $user = User::create([
    //                 'name' => $googleUser->getName(),
    //                 'email' => $googleUser->getEmail(),
    //                 'password' => Hash::make(Str::random(24)),
    //                 'google_id' => $googleUser->getId(),
    //                 'email_verified_at' => now(), // Google-verified emails can be marked as verified
    //             ]);

    //             Auth::login($user);
    //             return redirect()->route('dashboard')
    //                 ->with('success', 'Registration with Google successful!');
    //         }

    //         // Login case
    //         if (empty($user->google_id)) {
    //             // User exists but didn't use Google before - update their record
    //             $user->update(['google_id' => $googleUser->getId()]);
    //         }

    //         Auth::login($user);
    //         return redirect()->route('dashboard')
    //             ->with('success', 'Logged in with Google!');

    //     } catch (\Exception $e) {
    //         return redirect()->route('login')
    //             ->withErrors([
    //                 'email' => 'Google authentication failed. Please try again.'
    //             ]);
    //     }
    // }

    /**
     * Redirect to Google for authentication
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');
        return $driver
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Handle Google callback
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            // Configure HTTP client with SSL verification
            $httpClient = new Client([
                'verify' => $this->getCertificatePath()
            ]);

            /** @var GoogleProvider $driver */
            $driver = Socialite::driver('google');

            /** @var SocialiteUser $googleUser */
            $googleUser = $driver
                ->setHttpClient($httpClient)
                ->stateless()
                ->user();

            Log::info('Google User:', ['email' => $googleUser->getEmail()]);

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(24)),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                ]
            );

            // Update google_id if missing
            if (empty($user->google_id)) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            Auth::login($user);
            return redirect()->route('dashboard')
                ->with('success', $user->wasRecentlyCreated ?
                    'Google registration successful!' :
                    'Google login successful!');

        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google authentication failed. Please try again.'
                ]);
        }
    }

    /**
     * Get appropriate certificate path based on environment
     *
     * @return string|bool
     */
    protected function getCertificatePath()
    {
        return app()->environment('local')
            ? false // Disable verification in local
            : base_path('cacert.pem'); // Use certificate in production
    }

}
