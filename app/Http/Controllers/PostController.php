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
            // Store on the configured default disk (S3 in production, 'public' locally)
            $path = $request->file('image')->store('posts', config('filesystems.default'));
            $postData['image_path'] = $path;
        }

        $request->user()->posts()->create($postData);

        return redirect()->route('dashboard');
    }

    public function update(Request $request, \App\Models\Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'content' => ['nullable', 'string', 'max:280'],
        ]);

        $post->update(['content' => $validated['content'] ?? $post->content]);

        return back();
    }

    public function destroy(\App\Models\Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        // Delete stored image if present
        if ($post->image_path) {
            \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($post->image_path);
        }

        $post->delete();

        return back();
    }
}