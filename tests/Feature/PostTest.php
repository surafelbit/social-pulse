<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_post_with_an_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/posts', [
                'content' => 'Check out this photo',
                'image' => UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg'),
            ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'Check out this photo',
        ]);

        $post = Post::latest()->first();
        $this->assertNotNull($post->image_path);
        Storage::disk('public')->assertExists($post->image_path);
    }
}
