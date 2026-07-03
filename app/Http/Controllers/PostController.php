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
            'posts' => Post::with('user:id,name,username')->latest()->get()
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