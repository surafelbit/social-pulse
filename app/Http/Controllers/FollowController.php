<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
   public function toggle(User $user)
    {
        // Toggle the relationship: if it exists, remove it; if not, add it.
        auth()->user()->following()->toggle($user->id);

        return back();
    }
}