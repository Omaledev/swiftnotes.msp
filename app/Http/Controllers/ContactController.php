<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;

// class ContactController extends Controller
// {
//     public function index()
//     {
//         $user = Auth::user();

//         // Get team members from all teams the user belongs to
//         $contacts = User::whereHas('teams', function($query) use ($user) {
//             $query->whereIn('id', $user->teams()->pluck('teams.id'));
//         })
//         ->where('id', '!=', $user->id)
//         ->with(['teams' => function($query) use ($user) {
//             $query->whereIn('id', $user->teams()->pluck('teams.id'));
//         }])
//         ->get();

//         return view('contacts.index', [
//             'contacts' => $contacts
//         ]);
//     }
// }
