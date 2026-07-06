<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['post_id' => 'required|exists:posts,id']);
        $post = Post::findOrFail($request->post_id);

        $alreadyLiked = $request->user()->likes()->where('post_id', $post->id)->exists();

        if (! $alreadyLiked) {
            $request->user()->likes()->create(['post_id' => $post->id]);
            NotificationService::like($post, $request->user());
        }

        return back();
    }

    public function destroy(Post $post)
    {
        $request = request();
        $deleted = $request->user()->likes()->where('post_id', $post->id)->delete();

        if ($deleted) {
            NotificationService::removeLike($post, $request->user());
        }

        return back();
    }
}
