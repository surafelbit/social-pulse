<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['post_id' => 'required|exists:posts,id']);
        $post = Post::findOrFail($request->post_id);

        // Check if the like already exists
        $alreadyLiked = $request->user()->likes()->where('post_id', $post->id)->exists();

        if (!$alreadyLiked) {
            // 1. Save the like
            $request->user()->likes()->create(['post_id' => $post->id]);

            // 2. Fire the notification (if it's not our own post)
            if ($post->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'sender_id' => auth()->id(),
                    'type' => 'like',
                    'reference_id' => $post->id,
                ]);
            }
        }
        return back();
    }
    
    // Your destroy method can stay as it is.
}