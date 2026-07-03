<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $posts = $user->posts()
            ->with(['user:id,name,username', 'comments.user:id,username'])
            ->latest()
            ->get()
            ->map(function ($post) {
                $post->isLiked = $post->likes()->where('user_id', auth()->id())->exists();
                $post->likes_count = $post->likes()->count();
                $post->newComment = '';
                return $post;
            });

        return Inertia::render('PublicProfile', [
            'profileUser' => $user,
            'posts' => $posts,
            'isFollowing' => auth()->user()->following()->where('following_id', $user->id)->exists(),
        ]);
    }
}