<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\CartController;
use App\Models\User;
use App\Models\ShoppingCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createShoppingCart($user)
    {
        return ShoppingCart::create([
            "email_user" => $user->email,
            "username_user" => $user->username,
            "cart" => json_encode([]),
        ]);
    }

    public function test_show_cart_method_with_invalid_email(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => 'invalid@gmail.com',
        ]);

        $cartController = new CartController();
        $response = $cartController->show_cart($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
    }

    public function test_show_cart_method_with_valid_email(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);
        
        $request = new Request([
            'email' => $user->email,
        ]);

        $cartController = new CartController();
        $response = $cartController->show_cart($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }
    
    public function test_store_method_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => $user->email,
            'item_id' => 'invalidData',
            'name_product' => 'Product A',
            'price' => 10.00,
        ]);

        $cartController = new CartController();
        $response = $cartController->store($request);
    
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
    }

    public function test_store_method_with_valid_data(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => $user->email,
            'item_id' => 1,
            'name_product' => 'Product A',
            'price' => 10.00,
        ]);

        $cartController = new CartController();
        $response = $cartController->store($request);
    
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }
    
    public function test_store_method_with_same_item_id(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => $user->email,
            'item_id' => 1,
            'name_product' => 'Product A',
            'price' => 10.00,
        ]);
        
        $cartController = new CartController();
        $cartController->store($request);
        
        $newRequest = new Request([
            'email' => $user->email,
            'item_id' => 1,
            'name_product' => 'Product A',
            'price' => 10.00,
        ]);

        $response = $cartController->store($newRequest);
    
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
    }
    
    public function test_increase_quantity_method_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => $user->email,
            'rowId' => 10,
        ]);

        $cartController = new CartController();
        $cartController->show_cart($request);
        $cartController->store($request);
        $response = $cartController->increaseQuantity($request);
    
        if($response->getStatusCode() == 422)
        {
            $this->assertEquals(422, $response->getStatusCode());
            $this->assertEquals(false, $response->getData()->status);
        } else
        {
            $this->assertEquals(404, $response->getStatusCode());
            $this->assertEquals(false, $response->getData()->status);
        }

    }

    public function test_increase_quantity_method_with_valid_data(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => $user->email,
            'rowId' => 1,
        ]);

        $cartController = new CartController();
        $cartController->show_cart($request);
        $cartController->store($request);
        $response = $cartController->increaseQuantity($request);
    
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }

    public function test_decrease_quantity_method_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => 'email@invalid.com',
            'rowId' => 1,
        ]);
        
        $cartController = new CartController();
        $cartController->show_cart($request);
        $cartController->store($request);
        $response = $cartController->decreaseQuantity($request);
    
        if($response->getStatusCode() == 422)
        {
            $this->assertEquals(422, $response->getStatusCode());
            $this->assertEquals(false, $response->getData()->status);
        } else
        {
            $this->assertEquals(404, $response->getStatusCode());
            $this->assertEquals(false, $response->getData()->status);
        }
    }

    public function test_decrease_quantity_method_with_valid_data(): void
    {
        $user = User::factory()->create();
        $cart = $this->createShoppingCart($user);

        $request = new Request([
            'email' => $user->email,
            'rowId' => 1,
        ]);

        $cartController = new CartController();
        $cartController->show_cart($request);
        $cartController->store($request);
        $response = $cartController->decreaseQuantity($request);
    
        if($response->getStatusCode() == 400)
        {
            $this->assertEquals(400, $response->getStatusCode());
            $this->assertEquals(false, $response->getData()->status);
        } else {
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals(true, $response->getData()->status);
        }
    }
}
