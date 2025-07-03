<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team_member extends Model
{
    protected $table ='team_members';

    protected $primaryKey = 'id';

    protected $fillable = [
        'team_id',    
        'user_id',    
        'role',       
        'joined_at'   
    ];

    public function user() {
    return $this->belongsTo(User::class);
    }

    public function team() {
    return $this->belongsTo(Team::class);
   }
}
