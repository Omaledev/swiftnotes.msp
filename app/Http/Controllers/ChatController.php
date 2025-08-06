<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $teams = $user->teams()->with(['members', 'messages' => function($query) {
            $query->latest()->limit(50);
        }])->get();

        return view('chat.index', [
            'teams' => $teams,
            'user' => $user
        ]);
    }
}
