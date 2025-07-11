<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\str;

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

           return redirect()->intended('dashboard')
           ->with('success', 'You have successfully login!');
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

}
