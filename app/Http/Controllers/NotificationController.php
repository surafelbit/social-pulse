<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->with('sender:id,name,username')
            ->latest()
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'reference_id' => $notification->reference_id,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toISOString(),
                    'sender' => $notification->sender ? [
                        'id' => $notification->sender->id,
                        'name' => $notification->sender->name,
                        'username' => $notification->sender->username,
                    ] : null,
                ];
            });

        NotificationService::markAllRead($user);

        return Inertia::render('Notifications', [
            'notifications' => $notifications,
        ]);
    }
}
