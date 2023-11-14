<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Models\Product;
use App\Http\Controllers\Api\CatalogController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createProduct()
    {
        $product = new Product();

        $product->name_product = fake()->unique()->name();
        $product->category = 'test_category';
        $product->sku = 'test';
        $product->image = fake()->unique()->name();
        $product->rating = 1.0;
        $product->price = 100.00;
        $product->save();
    }

    public function test_index_method(): void
    {
        $product = $this->createProduct();
        
        $catalogController = new CatalogController();

        $response = $catalogController->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }

    public function test_index_filtered_method_with_valid_category(): void
    {
        $product = $this->createProduct();

        $catalogController = new CatalogController;

        $request = new Request([
            'category' => 'test_category',
        ]);

        $response = $catalogController->index_filtered($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }

    public function test_index_filtered_method_with_invalid_category(): void
    {
        $product = $this->createProduct();

        $catalogController = new CatalogController();

        $request = new Request([
            'category' => 'NonexistentCategory', 
        ]);

        $response = $catalogController->index_filtered($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Category not found!', $response->getData()->message);
    }

    public function test_show_products_method(): void
    {
        $product = new Product();

        $product->name_product = fake()->unique()->name();
        $product->category = 'test_category';
        $product->sku = 'test';
        $product->image = fake()->unique()->name();
        $product->rating = 1.0;
        $product->price = 100.00;
        $product->save();

        $catalogController = new CatalogController();

        $request = new Request([
            'name_product' => $product->name_product, 
        ]);

        $response = $catalogController->show($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertNotNull($response->getData()->products);
        $this->assertCount(1, $response->getData()->catalogs);
    }
}

