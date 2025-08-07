<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Note;
use App\Models\Team;


class SearchController extends Controller
{
     public function index(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->back();
        }

        /** @var User $user */
        $user = Auth::user(); // Using facade with type hint
        
        // Search notes
        $notes = Note::with(['team' => function($q) {
            $q->select('id', 'name'); 
        }])
        ->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('content', 'like', "%{$query}%");
        })
        ->whereHas('team', function($q) use ($user) {
            $q->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
        ->with(['creator'])
        ->limit(5)
        ->get();

        // Search teams
        $teams = $user->teams()
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return view('search.results', compact('query', 'notes', 'teams'));
    }

}
