<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // This tells Laravel which fields can be filled by a user
    protected $fillable = ['content'];

    // Relationships: A post belongs to a user, and has many likes and comments
    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function likes() 
    { 
        return $this->hasMany(Like::class); 
    }

    public function comments() 
    { 
        return $this->hasMany(Comment::class); 
    }
}