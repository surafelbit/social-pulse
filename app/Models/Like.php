<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    // The fields we are allowed to save to the database
    protected $fillable = ['user_id', 'post_id'];

    // A like belongs to a specific post
    public function post() { return $this->belongsTo(Post::class); }
    
    // A like belongs to the user who clicked it
    public function user() { return $this->belongsTo(User::class); }
}