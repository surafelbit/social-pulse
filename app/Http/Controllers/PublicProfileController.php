<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $posts = $user->posts()
            ->with(['user:id,name,username', 'comments.user:id,name,username'])
            ->latest()
            ->get()
            ->map(function ($post) {
                $post->isLiked = $post->likes()->where('user_id', auth()->id())->exists();
                $post->likes_count = $post->likes()->count();
                $post->newComment = '';
                return $post;
            });

        $authFollowingIds = auth()->user()->following()->pluck('following_id')->toArray();

        $followers = $user->followers()
            ->get(['users.id', 'users.name', 'users.username'])
            ->map(function ($f) use ($authFollowingIds) {
                $f->isFollowing = in_array($f->id, $authFollowingIds);
                return $f;
            });

        $following = $user->following()
            ->get(['users.id', 'users.name', 'users.username'])
            ->map(function ($f) use ($authFollowingIds) {
                $f->isFollowing = in_array($f->id, $authFollowingIds);
                return $f;
            });

        return Inertia::render('PublicProfile', [
            'profileUser' => $user,
            'posts' => $posts,
            'isFollowing' => auth()->user()->following()->where('following_id', $user->id)->exists(),
            'followers' => $followers,
            'following' => $following,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        if (blank($query)) {
            return response()->json([]);
        }

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'username']);

        return response()->json($users);
    }

    public function updateBio(Request $request)
    {
        $request->validate([
            'bio' => ['nullable', 'string', 'max:160'],
        ]);

        $user = auth()->user();
        $user->update(['bio' => $request->bio]);

        return back();
    }
}