<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteTeamController extends Controller
{
    public function destroy(Team $team)
    {
        // Only team owner can delete
        if ($team->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $team->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Team and all associated data deleted successfully!');
    }
}
