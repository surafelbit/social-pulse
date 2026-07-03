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
    $followingIds = $user->following()->pluck('following_id');
    $followingIds->push($user->id);

    // 1. Get the feed posts (like we planned)
    $posts = Post::with('user:id,name,username')
        ->whereIn('user_id', $followingIds)
        ->latest()
        ->get();

    // 2. Get suggested users (not the current user, and not already followed)
    $suggestedUsers = \App\Models\User::where('id', '!=', $user->id)
        ->whereNotIn('id', $followingIds)
        ->limit(5)
        ->get();

    return Inertia::render('Dashboard', [
        'posts' => $posts,
        'suggestedUsers' => $suggestedUsers, // Send this to Vue
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