<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_update_data(): void
    {
        $user = User::factory()->create();

        $userData = [
            'id' => $user->id,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'first_name' => 'newfirst',
            'last_name' => 'newlast',
            'gender' => 'M',
            'address' => 'new address',
            'telephone' =>  '0987654321',
        ];

        $response = $this->patch("/api/users/{$userData['id']}", $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "User Update successfully!",
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $userData['id'],
            'first_name' => 'newfirst',
            'last_name' => 'newlast',
            'gender' => 'M',
            'address' => 'new address',
            'telephone' =>  '0987654321',
        ]);
    }

    public function test_users_can_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this->delete("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "User Deleted successfully!",
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
