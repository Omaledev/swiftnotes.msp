<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailChangeToken extends Model
{
    public $timestamps = false; 
    protected $fillable = ['user_id', 'new_email', 'token', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];

    public static function createForUser($user_id, $new_email)
    {
        return self::create([
            'user_id' => $user_id,
            'new_email' => $new_email,
            'token' => Str::random(60),
            'created_at' => now()
        ]);
    }
}
