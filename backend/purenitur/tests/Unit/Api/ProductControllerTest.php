<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\ProductController;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_method_with_valid_data(): void
    {
        $request = new Request([
            'name_product' => 'Test Product',
            'category' => 'Test Category',
            'sku' => 'TEST123',
            'price' => 50.99,
            'image' => fake()->imageUrl(),
            'rating' => 4.5,
            'sold' => 100,
        ]);

        $controller = new ProductController();
        $response = $controller->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Product Created successfully!', $response->getData()->message);
    }

    public function test_store_method_with_duplicate_sku(): void
    {
        $existingProduct = Product::factory()->create(['sku' => 'DUP_SKU']);

        $request = new Request([
            'sku' => 'DUP_SKU',
        ]);

        $controller = new ProductController();
        $response = $controller->store($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Duplicate SKU!', $response->getData()->message);
    }

    public function test_show_method(): void
    {
        $product1 = Product::factory()->create(['sku' => 'SKU1',]);
        $product2 = Product::factory()->create(['sku' => 'SKU2',]);

        ProductDetail::factory()->create(['id' => $product1->id, 'sku' => $product1->sku,]);
        ProductDetail::factory()->create(['id' => $product2->id, 'sku' => $product2->sku,]);

        $productController = new ProductController();
        $response = $productController->show();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Product Created successfully!', $response->getData()->message);
        $this->assertCount(2, $response->getData()->products);
    }

    public function test_update_method_with_unavailable_product(): void
    {
        Product::factory()->create();
        $nonExistingProductId = 99999;

        $request = new Request([
            'id' => $nonExistingProductId,
        ]);

        $productController = new ProductController();
        $response = $productController->update($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Product Unavailable!', $response->getData()->message);
    }

    public function test_update_method_with_available_product_and_valid_data(): void
    {
        $product = Product::factory()->create();

        $request = new Request([
            'id' => $product->id,
            'name_product' => 'new product',
            'category' => 'new category',
            'sku' => 'new sku',
            'price' => $product->price,
            'image' => $product->image,
            'rating' => $product->rating,
            'sold' => $product->sold,
        ]);

        $productController = new ProductController();
        $response = $productController->update($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Product Update successfully!', $response->getData()->message);
    }

    public function test_update_method_with_available_product_but_invalid_data(): void
    {
        $product = Product::factory()->create();

        $request = new Request([
            'id' => $product->id,
            'name_product' => $product->name_product,
            'category' => $product->category,
            'sku' => $product->sku,
            'price' => 'invalid data',
            'image' => $product->image,
            'rating' => $product->rating,
            'sold' => $product->sold,
        ]);

        $productController = new ProductController();
        $response = $productController->update($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Validation not fulfilled!', $response->getData()->message);
    }

    public function test_destroy_method_with_unavailable_product(): void
    {
        Product::factory()->create();
        $nonExistingProductId = 99999;

        $request = new Request([
            'id' => $nonExistingProductId,
        ]);

        $productController = new ProductController();
        $response = $productController->destroy($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Product Unavailable!', $response->getData()->message);
    }

    public function test_destroy_method_with_available_product(): void
    {
        $product = Product::factory()->create();

        $request = new Request([
            'id' => $product->id,
        ]);

        $productController = new ProductController();
        $response = $productController->destroy($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertEquals('Product Deleted successfully!', $response->getData()->message);
    }
}

