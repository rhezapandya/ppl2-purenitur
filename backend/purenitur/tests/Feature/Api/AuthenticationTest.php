<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_users_can_login_with_valid_data(): void
    {
        $user = User::factory()->create();

        $userData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('api/login', $userData);

        $response->assertStatus(201)->assertJsonStructure(['user', 'token']);
    }

    public function test_users_can_not_login_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $userData = [
            'email' => $user->email,
            'password' => 'wrong-password',
        ];

        $response = $this->post('api/login', $userData);

        $response->assertStatus(404);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $userData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $loginResponse = $this->postJson('/api/login', $userData);

        $token = $loginResponse['token'];

        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];

        $logoutResponse = $this->withHeaders($headers)->postJson('/api/logout');

        $logoutResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Successfully Logged Out',
            ]);
        $this->assertFalse($user->tokens->contains('id', $token));
    }
}
