<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\ShoppingCart;
use App\Http\Controllers\Api\CartController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_show_cart()
    {
        $user = User::factory()->create();

        $cartData = [
            1 => [
                "rowId" => 1,
                "item_id" => 1,
                "name_product" => "Product 1",
                "qty" => 2,
                "price" => "50.00",
                "tax" => "5.00",
                "subtotal" => "55.00",
            ]
        ];

        $cart = new ShoppingCart();
        $cart->email_user = $user->email;
        $cart->username_user = $user->username;
        $cart->cart = json_encode($cartData);
        $cart->save();

        $response = $this->json('GET', '/api/cart',  ['email' => $cart->email_user]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => $response->getData()->message,
                'carts' => json_decode($cart->cart, true),
            ]);
    }

    public function test_users_can_add_item_to_cart()
    {
        $user = User::factory()->create();
        
        $cartData = [
            1 => [
                "rowId" => 1,
                "item_id" => 1,
                "name_product" => "Product 1",
                "qty" => 2,
                "price" => "50.00",
                "tax" => "5.00",
                "subtotal" => "55.00",
            ]
        ];
        
        $cart = new ShoppingCart();
        $cart->email_user = $user->email;
        $cart->username_user = $user->username;
        $cart->cart = json_encode($cartData);
        $cart->save();
        
        $requestData = [
            'email' => $cart->email_user,
            'item_id' => 2,
            'name_product' => 'Product 2',
            'price' => 30.00,
        ];
        
        $response = $this->json('POST', '/api/cart/add', $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "Shopping cart updated successfully.",
            ]);

        $this->assertDatabaseHas('shopping_carts', [
            'email_user' => $user->email,
            'cart' => $response->getData()->carts->cart,
        ]);
    }

    public function test_users_can_increase_quantity_item_in_cart()
    {
        $user = User::factory()->create();

        $cartData = [
            1 => [
                "rowId" => 1,
                "item_id" => 1,
                "name_product" => "Product 1",
                "qty" => 2,
                "price" => "50.00",
                "tax" => "5.00",
                "subtotal" => "55.00",
            ]
        ];

        $cart = new ShoppingCart();
        $cart->email_user = $user->email;
        $cart->username_user = $user->username;
        $cart->cart = json_encode($cartData);
        $cart->save();

        $requestData = [
            'email' => $cart->email_user,
            'rowId' => 1,
        ];

        $response = $this->json('POST', '/api/cart/add-qty', $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "Quantity Increased!",
            ]);
        

        $this->assertDatabaseHas('shopping_carts', [
            'email_user' => $user->email,
            'cart' => json_encode($response->getData()->update),
        ]);
    }

    public function test_users_can_decrease_quantity_item_in_cart()
    {
        $user = User::factory()->create();

        $cartData = [
            1 => [
                "rowId" => 1,
                "item_id" => 1,
                "name_product" => "Product 1",
                "qty" => 2,
                "price" => "50.00",
                "tax" => "5.00",
                "subtotal" => "55.00",
            ]
        ];

        $cart = new ShoppingCart();
        $cart->email_user = $user->email;
        $cart->username_user = $user->username;
        $cart->cart = json_encode($cartData);
        $cart->save();

        $requestData = [
            'email' => $cart->email_user,
            'rowId' => 1,
        ];

        $response = $this->json('POST', '/api/cart/dec-qty', $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => "Quantity Decreased!",
            ]);

        $this->assertDatabaseHas('shopping_carts', [
            'email_user' => $user->email,
            'cart' => json_encode($response->getData()->update),
        ]);
    }
}

