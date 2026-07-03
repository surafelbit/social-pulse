<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $user)
    {
        // 'attach' adds a row to the followers table
        auth()->user()->following()->attach($user->id);
        return back();
    }

    public function destroy(User $user)
    {
        // 'detach' removes the row from the followers table
        auth()->user()->following()->detach($user->id);
        return back();
    }
}