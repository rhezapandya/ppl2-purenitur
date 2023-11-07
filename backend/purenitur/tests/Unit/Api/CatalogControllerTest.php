<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\CatalogController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $catalogController = new CatalogController();

        $response = $catalogController->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }

    public function test_index_filtered_with_valid_category()
    {
        $catalogController = new CatalogController;

        $request = new Request([
            'category' => 'Bantal',
        ]);

        $response = $catalogController->index_filtered($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
    }

    public function test_index_filtered_with_invalid_category()
    {
        $catalogController = new CatalogController();

        $request = new Request([
            'category' => 'NonexistentCategory', 
        ]);

        $response = $catalogController->index_filtered($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(false, $response->getData()->status);
        $this->assertEquals('Category not found!', $response->getData()->message);
    }

    public function test_show_products()
    {
        $catalogController = new CatalogController();

        $request = new Request([
            'name_product' => 'GRILLTIDER', 
        ]);

        $response = $catalogController->show($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->status);
        $this->assertNotNull($response->getData()->products);
        $this->assertNotEmpty($response->getData()->products);
        $this->assertCount(5, $response->getData()->catalogs);
    }
}

