<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
   public function index()
{
    $user = auth()->user();
    $followingIds = $user->following()->pluck('following_id')->push($user->id);

    $posts = \App\Models\Post::with(['user:id,name,username', 'comments.user:id,username'])
        ->whereIn('user_id', $followingIds)
        ->latest()
        ->get()
        ->map(function ($post) use ($user) {
            // Add a temporary 'isLiked' boolean to every post object
            $post->isLiked = $post->likes()->where('user_id', $user->id)->exists();
            $post->likes_count = $post->likes()->count();
            return $post;
        });

    return Inertia::render('Dashboard', [
        'posts' => $posts,
        'suggestedUsers' => \App\Models\User::whereNotIn('id', $followingIds)->limit(5)->get(),
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $request->user()->posts()->create($validated);

        return redirect()->route('dashboard');
    }
}