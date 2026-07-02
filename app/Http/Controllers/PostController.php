<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
{
    return Inertia::render('Dashboard', [
        'posts' => Post::with(['user'])
            ->withCount('likes') // This adds the 'likes_count' to each post
            ->latest()
            ->get(),
    ]);
}

    public function store(Request $request)
    {
        // Validates that the post has content
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        // Saves the post linked to the logged-in user
        $request->user()->posts()->create($validated);

        return back();
    }
}