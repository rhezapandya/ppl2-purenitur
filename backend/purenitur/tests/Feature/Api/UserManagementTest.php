<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_data(): void
    {
        $admin = User::factory()->create([
            'is_admin' => '1',
        ]);

        $loginData = [
            'email' => $admin->email,
            'password' => 'password',
        ];
        
        $loginResponse = $this->post('/api/login', $loginData);

        $token = $loginResponse['token'];

        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];

        $updateData = [
            'id' => $admin->id,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'first_name' => 'newfirst',
            'last_name' => 'newlast',
            'gender' => 'M',
            'address' => 'new address',
            'telephone' =>  '0987654321',
        ];

        $updateResponse = $this->withHeaders($headers)->patch('/api/users/'.$updateData['id'], $updateData);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "User Update successfully!",
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $updateData['id'],
            'first_name' => 'newfirst',
            'last_name' => 'newlast',
            'gender' => 'M',
            'address' => 'new address',
            'telephone' =>  '0987654321',
        ]);
    }

    public function test_users_can_delete_account(): void
    {
        $admin = User::factory()->create([
            'is_admin' => '1',
        ]);

        $loginData = [
            'email' => $admin->email,
            'password' => 'password',
        ];
        
        $loginResponse = $this->post('/api/login', $loginData);

        $token = $loginResponse['token'];

        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];

        $deleteResponse = $this->withHeaders($headers)->delete('/api/users/'.$admin->id);

        $deleteResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "User Deleted successfully!",
            ]);

        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
    }
}
