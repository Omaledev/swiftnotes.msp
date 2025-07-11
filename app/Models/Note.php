<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title',
        'content',
        'team_id',
        'created_by'
    ];

    // Relationship: Note belongs to a User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship: Note belongs to a Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Relationship: Active editors (users editing the note)
    public function activeEditors()
    {
        return $this->belongsToMany(User::class, 'notes_session')
            ->using(NoteSession::class)
            ->withPivot('active_at');
    }
}
