<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table ='teams';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',          
        'invite_code',   
        'created_by'     
    ];

   
    public function members() {
        return $this->belongsToMany(User::class, 'team_members')
        ->withPivot('role');
    }

    public function owner() {
    return $this->belongsTo(User::class, 'created_by');
     }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    
}
