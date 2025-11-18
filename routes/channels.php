<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Team;
use App\Models\Note;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('team.{teamId}', function ($user, $teamId) {
//     // Verify user belongs to the team
//     return $user->teams()->where('teams.id', $teamId)->exists();
// });

// Broadcast::channel('note.{noteId}', function ($user, $noteId) {
//     // Verify user has access to the note
//     return $user->notes()->where('notes.id', $noteId)->exists() ||
//            $user->teams()->whereHas('notes', function($q) use ($noteId) {
//                $q->where('id', $noteId);
//            })->exists();
// });



// Broadcast::channel('team.{teamId}', function ($user, $teamId) {
//     // Verify user belongs to the team
//     return $user->teams()->where('teams.id', $teamId)->exists()
//         ? ['id' => $user->id, 'name' => $user->name]
//         : false;
// });

// Broadcast::channel('note.{noteId}', function ($user, $noteId) {
//     $note = Note::find($noteId);
//     if (!$note) return false;

//     // Verify user has access to the note through team membership
//     return $user->teams()->where('teams.id', $note->team_id)->exists()
//         ? ['id' => $user->id, 'name' => $user->name]
//         : false;
// });


// routes/channels.php
Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    return $user->teams->contains($teamId);
}, ['guards' => ['sanctum']]);

Broadcast::channel('note.{noteId}', function ($user, $noteId) {
    $note = \App\Models\Note::findOrFail($noteId);
    return $user->teams->contains($note->team_id);
}, ['guards' => ['sanctum']]);
