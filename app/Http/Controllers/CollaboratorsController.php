<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class CollaboratorsController extends Controller
{
    public function index()
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get team IDs first
        $teamIds = $user->teams()->pluck('teams.id');
        
        $collaborators = User::whereHas('teams', function($query) use ($teamIds) {
                $query->whereIn('teams.id', $teamIds);  // Explicitly specify table
            })
            ->with(['teams' => function($query) use ($teamIds) {
                $query->whereIn('teams.id', $teamIds)
                      ->select('teams.id', 'teams.name');
            }])
            ->where('users.id', '!=', $user->id)  // Explicit table for id
            ->select('users.id', 'users.name', 'users.email')
            ->orderBy('users.name')
            ->get();

        return view('pages.collaborators', compact('collaborators'));
    }
   
    
}
