<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    // This tells Laravel which fields can be filled by a user
    protected $fillable = ['content', 'image_path'];

    /**
     * Appended accessor so every serialised Post carries a ready-to-use URL.
     * Works for both S3 (returns a pre-signed or public URL) and local storage.
     */
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // When FILESYSTEM_DISK=s3 this returns the S3 object URL.
        // When using the local 'public' disk it falls back to the symlinked path.
        return Storage::disk(config('filesystems.default'))->url($this->image_path);
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