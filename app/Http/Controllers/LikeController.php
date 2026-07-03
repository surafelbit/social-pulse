<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['post_id' => 'required|exists:posts,id']);

        // Prevent duplicate likes
        $request->user()->likes()->updateOrCreate(
            ['post_id' => $request->post_id]
        );

        return back();
    }
    public function destroy(\App\Models\Post $post)
{
    $post->likes()->where('user_id', auth()->id())->delete();
    return back();
}
}