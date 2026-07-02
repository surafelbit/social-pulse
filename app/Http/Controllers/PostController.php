<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        // This renders the dashboard and sends all posts to your Vue component
        return Inertia::render('Dashboard', [
            'posts' => Post::with('user')->latest()->get(),
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