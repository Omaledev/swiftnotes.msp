<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token=null) {
        return view('password.reset-password',[
        'token' => $token,
        'email' => $request->email]);
    }

    public function reset (Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        // getting the token record
        $record = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

        if(!$record) {
            return back()->withErrors(['email' => 'Invalid token']);
        }

        // Hash the incoming token and compare with stored hash
        $hashedToken = hash('sha256', $request->token);
        if ($hashedToken !==$record->token) {
         return back()->withErrors(['token' =>'Invalid token']);
        }

        // Checking the token expiration of (60 minutes)
        $tokenExpireTime = Carbon::parse($record->created_at)->addMinutes(60);
        if(Carbon::now()->gt($tokenExpireTime)) {
            return back()->withErrors(['token' => 'Token has expired']);
        }

        // Updating the user's password
        User::where('email', $request->email)
        ->update(['password' => Hash::make($request->password)]);

        // Deleting the used token
        DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->delete();

        return redirect()->route('login')->with('status', 'Your password has been updated!');
    }
}


