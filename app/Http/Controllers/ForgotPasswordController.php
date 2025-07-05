<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\PasswordResetMail;
use Carbon\carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm () {
        return view('password.forgot-password');
    }

    public function sendResetLinkEmail(Request $request) {
       $request->validate(['email' => 'required|email']);
       $user = User::where('email', $request->email)->first();

       if(!$user) {
        return back()->withErrors(['email'=> 'User does not exist']);
       }

    //  Generating a token and hashing it
       $token = Str::random(64);
       $hashedToken = hash('sha256', $token);

    //  Delete any existing tokens for this email first
        DB::table('password_reset_tokens')
         ->where('email', $user->email)
         ->delete();

    // Insert the new token
       DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => $hashedToken,
        'created_at' => Carbon::now()
      ]);

    // Sending the email with unhashed token
    $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . $user->email;
    Mail::to($user->email)->send(new PasswordResetMail($resetUrl));

      return back()->with('status', 'Your reset link has been sent to your email!');
    }
}

