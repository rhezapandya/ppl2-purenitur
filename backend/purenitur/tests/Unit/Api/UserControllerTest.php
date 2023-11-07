<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_store_method_with_duplicate_email(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'email' => $user->email,
            'username' => 'testusername',
        ]);

        $userController = new UserController;
        $response = $userController->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Duplicate Email!', $response->getData()->message);
    }

    public function test_store_method_with_duplicate_username(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'email' => 'testuser@gmail.com',
            'username' => $user->username,
        ]);

        $userController = new UserController;
        $response = $userController->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Duplicate Username!', $response->getData()->message);
    }

    public function test_store_method_with_valid_data(): void
    {   
        $user = User::factory()->make();

        $request = new Request([
            'email' => $user->email,
            'username' => $user->username,
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->gender,
            'address' => $user->address,
            'telephone' => $user->telephone,
        ]);

        $userController = new UserController;
        $response = $userController->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals("User Created successfully!", $response->getData()->message);
    }

    public function test_store_method_with_invalid_data(): void
    {   
        $request = new Request([
            'email' => 'testuser@invalid.com',
            'username' => 'testuser',
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'gender' => 'M',
            'address' => 'Bojongsoang',
            'telephone' => '08124435366',
        ]);

        $userController = new UserController;
        $response = $userController->store($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Validation not fulfilled!', $response->getData()->message);
    }

    public function test_login_method_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $userController = new UserController;
        $response = $userController->login($request);
        
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function test_login_method_with_valid_data(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'email' => $user->email,
            'password' => 'password',
        ]);

        $userController = new UserController;
        $response = $userController->login($request);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_update_method_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'id' => $user->id,
            'password' => 'invalid',
            'password_confirmation' => 'invalid',
            'first_name' => 'first_baru',
            'last_name' => 'last_baru',
            'gender' => 'F',
            'address' => 'Alamat Baru',
            'telephone' => '08127462517',
        ]);

        $userController = new UserController;
        $response = $userController->update($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Validation not fulfilled!', $response->getData()->message);
    }

    public function test_update_method_with_valid_data(): void{
        $user = User::factory()->create();

        $request = new Request([
            'id' => $user->id,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'first_name' => 'first_baru',
            'last_name' => 'last_baru',
            'gender' => 'F',
            'address' => 'Alamat Baru',
            'telephone' => '08127462517',
        ]);

        $userController = new UserController;
        $response = $userController->update($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals("User Update successfully!", $response->getData()->message);
    }

    public function test_update_method_with_unavailable_user(): void
    {
        $nonExistingUserId = 99999;

        $request = new Request([
            'id' => $nonExistingUserId,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'first_name' => 'first_baru',
            'last_name' => 'last_baru',
            'gender' => 'F',
            'address' => 'Alamat Baru',
            'telephone' => '08127462517',
        ]);

        $userController = new UserController;
        $response = $userController->update($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals("User Unavailable!", $response->getData()->message);
    }

    public function test_delete_method_with_invalid_id(): void
    {
        $user = User::factory()->create();
        $nonExistingUserId = 99999;

        $request = new Request([
            'id' => $nonExistingUserId,
        ]);

        $userController = new UserController;
        $response = $userController->destroy($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals("User Unavailable!", $response->getData()->message);
    }
    
    public function test_delete_method_with_valid_id(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'id' => $user->id,
        ]);

        $userController = new UserController;
        $response = $userController->destroy($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals("User Deleted successfully!", $response->getData()->message);
    }
}
