<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_follow_and_unfollow_another_user(): void
    {
        $alice = User::factory()->create(['username' => 'alice']);
        $bob = User::factory()->create(['username' => 'bob']);

        // Alice follows Bob
        $response = $this
            ->actingAs($alice)
            ->post("/users/{$bob->id}/follow");

        $response->assertRedirect();
        $this->assertTrue($alice->following()->where('following_id', $bob->id)->exists());

        // Alice unfollows Bob
        $response = $this
            ->actingAs($alice)
            ->post("/users/{$bob->id}/follow");

        $response->assertRedirect();
        $this->assertFalse($alice->following()->where('following_id', $bob->id)->exists());
    }

    public function test_profile_page_contains_followers_and_following_lists(): void
    {
        $alice = User::factory()->create(['username' => 'alice']);
        $bob = User::factory()->create(['username' => 'bob']);

        // Alice follows Bob
        $this->actingAs($alice)->post("/users/{$bob->id}/follow");

        // View Bob's profile page as Alice
        $response = $this
            ->actingAs($alice)
            ->get("/profile/{$bob->username}");

        $response->assertOk();

        // Assert followers/following props are present in the response
        $response->assertInertia(fn ($page) => $page
            ->component('PublicProfile')
            ->has('followers', 1, fn ($page) => $page
                ->where('id', $alice->id)
                ->where('username', 'alice')
                ->where('isFollowing', false) // Alice is viewing, does Alice follow Alice? No
                ->etc()
            )
            ->has('following', 0)
        );

        // View Alice's profile page as Bob (Bob does not follow Alice)
        $response = $this
            ->actingAs($bob)
            ->get("/profile/{$alice->username}");

        $response->assertOk();

        $response->assertInertia(fn ($page) => $page
            ->component('PublicProfile')
            ->has('followers', 0)
            ->has('following', 1, fn ($page) => $page
                ->where('id', $bob->id)
                ->where('username', 'bob')
                ->where('isFollowing', false) // Bob is viewing, Bob doesn't follow Bob
                ->etc()
            )
        );
    }
}
