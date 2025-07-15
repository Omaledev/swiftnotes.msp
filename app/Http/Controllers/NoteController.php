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
        // $notes = $team->notes()->with(['creator', 'activeEditors'])->latest()->get();

         $notes = $team->notes()
                ->with(['creator', 'activeEditors'])
                ->latest()
                ->paginate(10);  // Changed from get() to paginate(10)
        $user = Auth::user();
        $isOwner = $team->created_by === $user->id;
        $memberCount = $team->members()->count();

        return view('notes.index', [
            'team' => $team,
            'notes' => $notes,
            'isOwner' => $isOwner,
            'memberCount' => $memberCount
        ]);
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

        return redirect()->route('notes.index', $team)
            ->with('success', 'Note created successfully!');
    }

    public function show(Note $note)
    {
        $note->load(['creator', 'team.members', 'activeEditors']);
        $canDelete = $note->created_by === Auth::id() || $note->team->created_by === Auth::id();

        return view('notes.show', [
            'note' => $note,
            'canDelete' => $canDelete,
            'team' => $note->team
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $note->update($request->only(['title', 'content']));

        return back()->with('success', 'Note updated successfully!');
    }

    public function destroy(Note $note)
{
    
    if (Auth::id() !== $note->created_by && Auth::id() !== $note->team->created_by) {
        abort(403, 'Unauthorized action.');
    }

    $note->delete();

    return redirect()->route('notes.index', $note->team)
        ->with('success', 'Note deleted successfully!');
   }

    public function startEditing(Note $note)
    {
        $note->activeEditors()->syncWithoutDetaching([Auth::id() => ['active_at' => now()]]);
        return back();
    }

    public function stopEditing(Note $note)
    {
        $note->activeEditors()->detach(Auth::id());
        return back();
    }
}
