<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_liking_a_post_creates_notification_for_post_owner(): void
    {
        $owner = User::factory()->create();
        $liker = User::factory()->create();
        $post = $owner->posts()->create(['content' => 'Hello world']);

        $this->actingAs($liker)->post('/likes', ['post_id' => $post->id]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $owner->id,
            'sender_id' => $liker->id,
            'type' => 'like',
            'reference_id' => $post->id,
            'is_read' => false,
        ]);
    }

    public function test_liking_own_post_does_not_create_notification(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['content' => 'My post']);

        $this->actingAs($user)->post('/likes', ['post_id' => $post->id]);

        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_unliking_a_post_removes_notification(): void
    {
        $owner = User::factory()->create();
        $liker = User::factory()->create();
        $post = $owner->posts()->create(['content' => 'Hello world']);

        $this->actingAs($liker)->post('/likes', ['post_id' => $post->id]);
        $this->actingAs($liker)->delete("/likes/{$post->id}");

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $owner->id,
            'sender_id' => $liker->id,
            'type' => 'like',
            'reference_id' => $post->id,
        ]);
    }

    public function test_following_a_user_creates_notification(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $this->actingAs($alice)->post("/users/{$bob->id}/follow");

        $this->assertDatabaseHas('notifications', [
            'user_id' => $bob->id,
            'sender_id' => $alice->id,
            'type' => 'follow',
            'is_read' => false,
        ]);
    }

    public function test_unfollowing_a_user_removes_notification(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $this->actingAs($alice)->post("/users/{$bob->id}/follow");
        $this->actingAs($alice)->post("/users/{$bob->id}/follow");

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $bob->id,
            'sender_id' => $alice->id,
            'type' => 'follow',
        ]);
    }

    public function test_commenting_on_a_post_creates_notification(): void
    {
        $owner = User::factory()->create();
        $commenter = User::factory()->create();
        $post = $owner->posts()->create(['content' => 'Hello world']);

        $this->actingAs($commenter)->post("/posts/{$post->id}/comments", [
            'content' => 'Nice post!',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $owner->id,
            'sender_id' => $commenter->id,
            'type' => 'comment',
            'is_read' => false,
        ]);
    }

    public function test_commenting_on_own_post_does_not_create_notification(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['content' => 'My post']);

        $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Self comment',
        ]);

        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_notifications_page_lists_notifications_and_marks_them_read(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();

        Notification::create([
            'user_id' => $owner->id,
            'sender_id' => $sender->id,
            'type' => 'follow',
            'is_read' => false,
        ]);

        $response = $this->actingAs($owner)->get('/notifications');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Notifications')
            ->has('notifications', 1)
            ->where('notifications.0.type', 'follow')
        );

        $this->assertDatabaseHas('notifications', [
            'user_id' => $owner->id,
            'is_read' => true,
        ]);
    }

    public function test_unread_count_is_shared_with_inertia(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();

        Notification::create([
            'user_id' => $owner->id,
            'sender_id' => $sender->id,
            'type' => 'follow',
            'is_read' => false,
        ]);

        $response = $this->actingAs($owner)->get('/dashboard');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->where('auth.unreadNotifications', 1)
        );
    }
}
