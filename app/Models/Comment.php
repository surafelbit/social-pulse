<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // The fields we are allowed to save to the database
    protected $fillable = ['user_id', 'post_id', 'body'];

    // A comment belongs to a post
    public function post() { return $this->belongsTo(Post::class); }
    
    // A comment belongs to the user who wrote it
    public function user() { return $this->belongsTo(User::class); }
}