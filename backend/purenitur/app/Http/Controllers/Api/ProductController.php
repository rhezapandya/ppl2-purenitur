<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $check_sku = Product::where('sku', $request->sku)->first();

        if ($check_sku) {
            return response()->json([
                'status' => false,
                'message' => "Duplicate SKU!",
            ], 422);
        } else {
            $check_validation = Validator::make($request->all(), [
                'name_product' => ['string', 'max:255'],
                'category' => ['string', 'max:255'],
                'sku' => ['string', 'max:12'],
                'price' => ['numeric', 'max:999999999.99'],
                'image' => ['string', 'max:255'],
                'rating' => ['numeric', 'max:5.00'],
                'sold' => ['integer', 'max:255'],
            ]);

            if ($check_validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation not fulfilled!',
                    'errors' => $check_validation->errors(),
                ], 422);
            } else {
                $products = Product::create([
                    "name_product" => $request->name_product,
                    "category" => $request->category,
                    "sku" => $request->sku,
                    "price" => $request->price,
                    "image" => $request->image,
                    "rating" => $request->rating,
                    "sold" => $request->sold
                ]);

                return response()->json([
                    'status' => true,
                    'message' => "Product Created successfully!",
                    'products' => $products
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $products = Product::select('*')
            ->join('product_details', 'products.id', '=', 'product_details.id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => "Product Created successfully!",
            'products' => $products
        ], 200);
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
    public function update(Request $request)
    {
        $check_avail = Product::find($request->id);

        if ($check_avail) {
            $check_validation = Validator::make($request->all(), [
                'name_product' => ['string', 'max:255'],
                'category' => ['string', 'max:255'],
                'sku' => ['string', 'max:12'],
                'price' => ['numeric', 'max:999999999.99'],
                'image' => ['string', 'max:255'],
                'rating' => ['numeric', 'max:5.00'],
                'sold' => ['integer', 'max:255'],
            ]);

            if ($check_validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation not fulfilled!',
                    'errors' => $check_validation->errors(),
                ], 422);
            } else {
                $product = Product::where('id', $request->id)
                    ->update([
                        "name_product" => $request->name_product,
                        "category" => $request->category,
                        "sku" => $request->sku,
                        "price" => $request->price,
                        "image" => $request->image,
                        "rating" => $request->rating,
                        "sold" => $request->sold
                    ]);

                return response()->json([
                    'status' => true,
                    'message' => "Product Update successfully!",
                    'product' => $product
                ], 200);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => "Product Unavailable!",
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $check_avail = Product::find($request->id);

        if ($check_avail) {
            $product = Product::where('id', $request->id)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => "Product Deleted successfully!",
                'product' => $product
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Product Unavailable!",
            ], 200);
        }
    }
}
