<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    // Verify user belongs to the team
    return $user->teams()->where('teams.id', $teamId)->exists();
});

Broadcast::channel('note.{noteId}', function ($user, $noteId) {
    // Verify user has access to the note
    return $user->notes()->where('notes.id', $noteId)->exists() || 
           $user->teams()->whereHas('notes', function($q) use ($noteId) {
               $q->where('id', $noteId);
           })->exists();
});