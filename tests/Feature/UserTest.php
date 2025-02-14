<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_a_user_to_login_successfully()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Login successful.',
                'error' => false,
            ]);

        $this->assertNotNull($response['data']['token']);
    }

    /** @test */
    public function it_allows_a_user_to_logout_successfully()
    {
        // Create a test user
        $user = User::factory()->create();

        // Authenticate the user and generate a token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Make the logout request with the valid token
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        // Assert the response status is 200
        $response->assertStatus(200)
            ->assertJson([
                'status'  => 200,
                'message' => 'Logout successful.',
                'error'   => false,
            ]);

        // Assert the token is revoked
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
}
