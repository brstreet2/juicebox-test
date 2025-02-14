<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication
        $this->user = User::factory()->create();
    }

    /**
     * Test creating a post as an authenticated user.
     */
    public function test_authenticated_user_can_create_post()
    {
        // Simulate authenticated user
        $this->actingAs($this->user);

        // Valid post data
        $postData = [
            'title'       => 'Test Title',
            'description' => 'This is a test description.',
            'content'     => 'This is the content of the test post.',
        ];

        // Send POST request
        $response = $this->postJson('/api/posts', $postData);

        // Assert response
        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'content',
                    'user_id',
                    'created_at',
                    'updated_at',
                ],
            ]);

        // Assert database
        $this->assertDatabaseHas('posts', [
            'title'       => $postData['title'],
            'description' => $postData['description'],
            'content'     => $postData['content'],
            'user_id'     => $this->user->id,
        ]);
    }

    /**
     * Test retrieving a list of posts.
     */
    public function test_it_returns_a_list_of_posts()
    {
        // Simulate authenticated user
        $this->actingAs($this->user);

        // Seed posts
        Post::factory()->count(3)->create(['user_id' => $this->user->id]);

        // Send GET request
        $response = $this->getJson('/api/posts');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'content',
                            'user_id',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ]);
    }
}
