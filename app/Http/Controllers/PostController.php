<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\CloudinaryService;

class PostController extends Controller
{
    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

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
            // Upload to Cloudinary and get the secure URL
            $imageUrl = $this->cloudinaryService->uploadImage($request->file('image'), 'posts');
            if ($imageUrl) {
                $postData['image_path'] = $imageUrl;
            } else {
                return back()->withErrors(['image' => 'Failed to upload image. Please try again.']);
            }
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

        // Images are stored on Cloudinary, so no need to delete them here
        // Cloudinary automatically handles cleanup with transformations

        $post->delete();

        return back();
    }
}