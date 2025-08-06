<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Team;
use App\Models\EmailChangeToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailChangeMail;

class SettingsController extends Controller
{
    // Show settings dashboard
    public function index()
    {
        /** @var User $user */ // Type hint for IDE
        $user = Auth::user();

        /** @var Collection<Team> $joinedTeams */ // Type hint for collection
        $joinedTeams = $user->teams()
            ->with('owner')
            ->where('created_by', '!=', $user->id)
            ->get();

        return view('pages.settings', [
            'user' => $user,
            'ownedTeams' => $user->createdTeams,
            'joinedTeams' => $joinedTeams
        ]);
    }

    // Update user's display name
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('pages.settings')->with('success', 'Name updated successfully!');
    }

    // Update user's email

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::id())
            ],
            'current_password' => 'required'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        if (strtolower($user->email) === strtolower($request->email)) {
            return back()->with('info', 'Email address unchanged');
        }

        // Create verification token
        $token = EmailChangeToken::createForUser($user->id, $request->email);

        // Send verification email
        Mail::to($request->email)->send(new VerifyEmailChangeMail($user, $token));

        return back()->with('success', 'Verification link sent to your new email address!');
    }

    public function verifyEmailChange(Request $request, $token)
    {
        $record = EmailChangeToken::where('token', $token)->first();

        if (!$record) {
            return redirect()->route('pages.settings')->with('error', 'Invalid verification link');
        }

        // Check expiration (24 hours)
        if ($record->created_at->addHours(24)->isPast()) {
            $record->delete();
            return redirect()->route('pages.settings')->with('error', 'Verification link expired');
        }

        // Update user's email
        $user = User::find($record->user_id);
        $user->email = $record->new_email;
        $user->save();

        // Delete token
        $record->delete();

        return redirect()->route('settings')->with('success', 'Email updated successfully!');
    }

        //  Reset password
    public function updatePassword (Request $request)
    {
        $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed'
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }

    /** @var User $user */
    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password updated successfully!');

    }

    // Update team name (only for team owners)
    public function updateTeamName(Request $request, Team $team)
    {
        if ($team->created_by !== Auth::id()) {
            abort(403, 'Only the team owner can change the name.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teams')->ignore($team->id)
            ]
        ]);

        $team->name = $request->name;
        $team->save();

        return back()->with('success', 'Team name updated successfully!');
    }

    // Delete user account
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required'
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!Hash::check($request->confirm_password, $user->password)) {
            throw ValidationException::withMessages([
                'confirm_password' => ['Incorrect password.']
            ]);
        }

        // Handle team ownership transfer
        foreach ($user->createdTeams as $team) {
            if ($team->members()->count() === 1) {
                $team->delete();
            } else {
                $newOwner = $team->members()
                    ->where('user_id', '!=', $user->id)
                    ->first();
                $team->created_by = $newOwner->id;
                $team->save();
            }
        }

          // Logout using Auth facade (explicitly specify 'web' guard)
          Auth::guard('web')->logout();

        // Delete user
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }
    

    // Delete a team (for owners)
    public function deleteTeam(Request $request, Team $team)
    {
        if ($team->created_by !== Auth::id()) {
            abort(403, 'Only the team owner can delete the team.');
        }

        $team->delete();

        return back()->with('success', 'Team deleted successfully!');
    }

    // Leave a team (for members)
    public function leaveTeam(Request $request, Team $team)
    {
        $user = Auth::user();

        if ($team->created_by === $user->id) {
            abort(403, 'Team owners cannot leave their own team. Transfer ownership first.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'You have left the team.');
    }
}
