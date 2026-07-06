<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationService;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return back();
        }

        $result = $authUser->following()->toggle($user->id);

        if (in_array($user->id, $result['attached'], true)) {
            NotificationService::follow($user, $authUser);
        } elseif (in_array($user->id, $result['detached'], true)) {
            NotificationService::removeFollow($user, $authUser);
        }

        return back();
    }
}
