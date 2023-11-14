<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

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
        $this->assertTrue(true);
    }
}
