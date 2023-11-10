<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        if ($products) {
            return response()->json([
                'status' => true,
                'products' => $products,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!',
            ], 422);
        }
    }

    /**
     * Display a listing of the resource filtered by category
     */
    public function index_filtered(Request $request)
    {
        $check_validation = Validator::make($request->route()->parameters(), [
            'category' => ['required', 'string'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $products = Product::where('category', '=', $request->category)->get();
            if ($products == null || count($products) <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found!',
                ], 422);
            } else {
                return response()->json([
                    'status' => true,
                    'products' => $products,
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $check_validation = Validator::make($request->route()->parameters(), [
            'name_product' => ['required', 'string'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $products = Product::select('*')
                ->join('product_details', 'products.sku', '=', 'product_details.sku')
                ->where('products.name_product', '=', $request->name_product)
                ->get();
            $catalogs = Product::inRandomOrder()->limit(5)->get();

            if ($products) {
                return response()->json([
                    'status' => true,
                    'products' => $products,
                    'catalogs' => $catalogs,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found!',
                ], 422);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
