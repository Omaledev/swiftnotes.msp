<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection; // Add this import

class DashboardController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */ // Type hint for IDE
        $user = Auth::user();

        /** @var Collection<Team> $joinedTeams */ // Type hint for collection
        $joinedTeams = $user->teams()
            ->with('owner')
            ->where('created_by', '!=', $user->id)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'ownedTeams' => $user->createdTeams,
            'joinedTeams' => $joinedTeams
        ]);
    }
}
