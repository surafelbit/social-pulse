<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function store(Request $request, \App\Models\Post $post)
{
    $request->validate(['content' => 'required|max:255']);
    
    $post->comments()->create([
        'user_id' => auth()->id(),
        'content' => $request->content,
    ]);
    
    return back();
}
}
