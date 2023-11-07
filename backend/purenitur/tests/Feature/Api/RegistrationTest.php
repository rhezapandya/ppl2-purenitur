<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $userData = [
            'email' => 'testuser@gmail.com',
            'username' => 'testuser',
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => 'first',
            'last_name' => 'last',
            'gender' => 'M',
            'address' => 'Bandung',
            'telephone' =>  '0987654321',
        ];

        $response = $this->post('/api/users', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' =>'User Created successfully!',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@gmail.com',
            'username' => 'testuser',
            'first_name' => 'first',
            'last_name' => 'last',
            'gender' => 'M',
            'address' => 'Bandung',
            'telephone' =>  '0987654321',
        ]);
    }
}
