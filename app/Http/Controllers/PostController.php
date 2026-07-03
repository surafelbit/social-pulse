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

    // Get IDs of people the user follows
    $followingIds = $user->following()->pluck('following_id');
    
    // Add the current user's own ID so they see their own posts
    $followingIds->push($user->id);

    $posts = Post::with('user:id,name,username')
        ->whereIn('user_id', $followingIds)
        ->latest()
        ->get();

    return Inertia::render('Dashboard', [
        'posts' => $posts,
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