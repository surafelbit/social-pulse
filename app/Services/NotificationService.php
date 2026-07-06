<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NotificationSent;
use App\Models\Post;
use App\Models\User;

class NotificationService
{
    public static function like($post, $user)
{
    if ($post->user_id !== $user->id) {
        $notification = Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $user->id,
            'type' => 'like',
            'reference_id' => $post->id,
        ]);

        // Fire the WebSocket broadcast!
        event(new NotificationSent($notification));
    }
}

    public static function removeLike(Post $post, User $sender): void
    {
        Notification::query()
            ->where('user_id', $post->user_id)
            ->where('sender_id', $sender->id)
            ->where('type', 'like')
            ->where('reference_id', $post->id)
            ->delete();
    }

    public static function follow(User $receiver, User $sender): void
    {
        if ($receiver->id === $sender->id) {
            return;
        }

        Notification::firstOrCreate(
            [
                'user_id' => $receiver->id,
                'sender_id' => $sender->id,
                'type' => 'follow',
                'reference_id' => null,
            ],
            ['is_read' => false],
        );
    }

    public static function removeFollow(User $receiver, User $sender): void
    {
        Notification::query()
            ->where('user_id', $receiver->id)
            ->where('sender_id', $sender->id)
            ->where('type', 'follow')
            ->delete();
    }

    public static function comment(Post $post, User $sender, int $commentId): void
    {
        if ($post->user_id === $sender->id) {
            return;
        }

        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $sender->id,
            'type' => 'comment',
            'reference_id' => $commentId,
            'is_read' => false,
        ]);
    }

    public static function markAllRead(User $user): void
    {
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);
    }

    public static function unreadCount(User $user): int
    {
        return $user->notifications()->where('is_read', false)->count();
    }
}
