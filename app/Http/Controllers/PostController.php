<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $followingIds = $user->following()->pluck('following_id')->push($user->id);

        $posts = \App\Models\Post::with(['user:id,name,username', 'comments.user:id,name,username'])
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->get()
            ->map(function ($post) use ($user) {
                $post->isLiked = $post->likes()->where('user_id', $user->id)->exists();
                $post->likes_count = $post->likes()->count();
                $post->newComment = '';
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
            'content' => ['nullable', 'string', 'max:280'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if (blank($request->input('content')) && !$request->hasFile('image')) {
            return back()->withErrors(['content' => 'Please add some text or choose a photo.']);
        }

        $postData = [
            'content' => $request->input('content', ''),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $postData['image_path'] = $path;
        }

        $request->user()->posts()->create($postData);

        return redirect()->route('dashboard');
    }
}