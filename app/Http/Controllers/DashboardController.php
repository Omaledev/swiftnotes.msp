<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Team;

class DashboardController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = Auth::user();


        $ownedTeams = $user->createdTeams;


        $joinedTeams = $user->teams()->where('teams.created_by', '!=', $user->id)->get();

        return view('dashboard', [
            'ownedTeams' => $ownedTeams,
            'joinedTeams' => $joinedTeams
        ]);
    }
}
