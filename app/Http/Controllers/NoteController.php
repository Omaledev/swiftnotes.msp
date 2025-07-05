<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Team;
use App\Models\NoteSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(Team $team)
    {
        $notes = $team->notes()->with(['creator', 'activeEditors'])->get();
        $user = Auth::user();
        $isOwner = $team->created_by === $user->id;
        $memberCount = $team->members()->count();

        return view('notes.index', compact('notes', 'team', 'isOwner', 'memberCount'));
    }

    public function store(Request $request, Team $team)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'team_id' => $team->id,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('notes.show', $note);
    }

    public function show(Note $note)
    {
        $note->load(['creator', 'team.members', 'activeEditors']);
        $user = Auth::user();
        $canDelete = $note->created_by === $user->id || $note->team->created_by === $user->id;

        return view('notes.show', compact('note', 'canDelete'));
    }

    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string'
        ]);

        $note->update($request->only(['title', 'content']));

        return response()->json(['success' => true]);
    }

    public function destroy(Note $note)
    {
        $user = Auth::user();
        if ($note->created_by !== $user->id && $note->team->created_by !== $user->id) {
            abort(403);
        }

        $note->delete();
        return redirect()->route('notes.index', $note->team);
    }

    public function startEditing(Note $note)
    {
        NoteSession::updateOrCreate(
            ['user_id' => Auth::id(), 'note_id' => $note->id],
            ['active_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function stopEditing(Note $note)
    {
        NoteSession::where('user_id', Auth::id())
            ->where('note_id', $note->id)
            ->delete();

        return response()->json(['success' => true]);
    }
}
