<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShowMembersController extends Controller
{
    public function showMembers(Team $team)
    {
        $members = $team->members()->paginate(10);
        return view('teams.members', compact('team', 'members'));
    }

    public function removeMember(Team $team, User $user)
    {
        // Only team owner can remove members (except themselves)
        if ($team->created_by !== Auth::id() || $user->id === Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Member removed successfully!');
    }
}
