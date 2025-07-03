<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table ='notes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',       
        'content',     
        'team_id',     
        'created_by'   
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
    return $this->belongsTo(Team::class);
    
   }

   public function activeEditors()
   {
    return $this->belongsToMany(User::class, 'notes_session')
        ->using(NoteSession::class)
        ->withPivot('active_at');
   }

   
}
