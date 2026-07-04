<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchAndBioTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_search_for_other_users(): void
    {
        $user1 = User::factory()->create(['name' => 'Alice Johnson', 'username' => 'alice']);
        $user2 = User::factory()->create(['name' => 'Bob Smith', 'username' => 'bob']);

        $response = $this
            ->actingAs($user1)
            ->getJson('/users/search?query=Alice');

        $response->assertOk();
        $response->assertJsonFragment([
            'name' => 'Alice Johnson',
            'username' => 'alice',
        ]);
        $response->assertJsonMissing([
            'name' => 'Bob Smith',
        ]);
    }

    public function test_guest_cannot_search_users(): void
    {
        $response = $this->getJson('/users/search?query=Alice');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_their_bio(): void
    {
        $user = User::factory()->create(['bio' => 'Old Bio']);

        $response = $this
            ->actingAs($user)
            ->patch('/profile/bio', [
                'bio' => 'New updated bio text.',
            ]);

        $response->assertRedirect();
        $this->assertEquals('New updated bio text.', $user->fresh()->bio);
    }

    public function test_guest_cannot_update_bio(): void
    {
        $response = $this->patch('/profile/bio', [
            'bio' => 'Guest attempt.',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_bio_max_length_validation(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile/bio', [
                'bio' => str_repeat('a', 161),
            ]);

        $response->assertSessionHasErrors('bio');
        $this->assertNull($user->fresh()->bio);
    }
}
