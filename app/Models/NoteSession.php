<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteSession extends Model
{
    protected $table = 'notes_session';

    protected $fillable = [
        'user_id',
        'note_id',
        'active_at'
    ];

    protected $casts = [
        'active_at' => 'datetime'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Note
    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
