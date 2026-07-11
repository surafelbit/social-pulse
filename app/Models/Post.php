<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // This tells Laravel which fields can be filled by a user
    protected $fillable = ['content', 'image_path'];

    /**
     * Appended accessor so every serialised Post carries a ready-to-use URL.
     * When using Cloudinary, image_path is already a full secure URL.
     */
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        // image_path from Cloudinary is already a complete secure URL
        return $this->image_path;
    }

    // Relationships: A post belongs to a user, and has many likes and comments
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
