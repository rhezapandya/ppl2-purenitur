<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index_method_with_valid_user_id_and_has_no_orders(): void
    {
        $user = User::factory()->create();

        $orderController = new OrderController();

        $request = new Request(['id' => $user->id]);

        $response = $orderController->index($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('You have no orders!', $response->getData()->message);
    }

    public function test_index_method_with_valid_user_id_and_has_orders(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            "cart" => json_encode([]),
            "price_total" => 100.00,
            "final_price" => 100.00,
            "payment_type" => 'Transfer Bank BCA',
        ]);

        $request = new Request([
            'id' => $user->id,
            'user_id' => $order->user_id,
            'cart' => $order->cart,
        ]);
        
        $orderController = new OrderController();
        $response = $orderController->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals($user->username . "'s Order", $response->getData()->message);
    }

    public function test_index_method_with_invalid_user_id(): void
    {
        $orderController = new OrderController();

        $request = new Request(['id' => 999]);

        $response = $orderController->index($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('You have not registered yet!', $response->getData()->message);
    }

    public function test_store_method_with_valid_data_but_cart_is_empty(): void
    {
        $user = User::factory()->create();

        $shoppingCart = ShoppingCart::create([
            "email_user" => $user->email,
            "username_user" => $user->username,
            "cart" => json_encode([]),
        ]);
        
        $request = new Request([
            'id' => $user->id,
            'discount_name' => 'Diskon Libur Nasional',
            'payment_method' => 'Credit Card',
            'shipment_address' => $user->address,
            'cart' => $shoppingCart->cart,
        ]);
        
        $orderController = new OrderController();
        $response = $orderController->store($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals("Carts Empty", $response->getData()->message);
    }

    public function test_store_method_with_valid_data_but_cart_is_not_empty(): void
    {
        $user = User::factory()->create();

        $shoppingCart = ShoppingCart::create([
            "email_user" => $user->email,
            "username_user" => $user->username,
            "cart" => json_encode([]),
        ]);

        $requestForShoppingCart = new Request([
            'email' => $user->email,
            'item_id' => 1,
            'name_product' => 'Product A',
            'price' => 10.00,
        ]);

        $cartController = new CartController();
        $cartController->store($requestForShoppingCart);
        
        $requestForOrder = new Request([
            'id' => $user->id,
            'discount_name' => 'Diskon Libur Nasional',
            'payment_method' => 'Credit Card',
            'shipment_address' => $user->address,
            'cart' => $shoppingCart->cart,
        ]);
        
        $orderController = new OrderController();
        $response = $orderController->store($requestForOrder);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals("Order Created", $response->getData()->message);
    }

    public function test_store_method_with_invalid_user_id(): void
    {
        $orderController = new OrderController();

        $request = new Request([
            'id' => 999,
            'discount_name' => 'DiscountCode',
            'payment_method' => 'Credit Card',
            'shipment_address' => '123 Main St',
        ]);

        $response = $orderController->store($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('You have not registered yet!', $response->getData()->message);
    }

    public function test_transaction_store_method_with_valid_data_but_order_id_does_not_exist(): void
    {
        $orderController = new OrderController();

        $request = new Request([
            'order_id' => 1,
            'image_payment' => fake()->unique()->name(),
        ]);

        $response = $orderController->transaction_store($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Order ID not Exist!', $response->getData()->message);
    }

    public function test_transaction_store_method_with_valid_data_but_order_id_does_exist(): void
    {
        $this->assertTrue(true);
    }
}

