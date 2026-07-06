<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|max:255']);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        NotificationService::comment($post, $request->user(), $comment->id);

        return back();
    }
}
