<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment_on_a_post(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['content' => 'Test post content']);

        $response = $this
            ->actingAs($user)
            ->post("/posts/{$post->id}/comments", [
                'content' => 'This is a test comment.',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'This is a test comment.',
        ]);
    }

    public function test_guest_cannot_comment_on_a_post(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['content' => 'Test post content']);

        $response = $this
            ->post("/posts/{$post->id}/comments", [
                'content' => 'Guest comment attempt.',
            ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'content' => 'Guest comment attempt.',
        ]);
    }

    public function test_comment_content_validation(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['content' => 'Test post content']);

        // Required validation
        $response = $this
            ->actingAs($user)
            ->post("/posts/{$post->id}/comments", [
                'content' => '',
            ]);

        $response->assertSessionHasErrors('content');

        // Max length validation (255 characters)
        $response2 = $this
            ->actingAs($user)
            ->post("/posts/{$post->id}/comments", [
                'content' => str_repeat('a', 256),
            ]);

        $response2->assertSessionHasErrors('content');
    }
}
