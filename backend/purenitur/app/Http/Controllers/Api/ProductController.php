<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductDetail;
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
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $check_validation_sku = Validator::make($request->all(), [
                    'sku' => ['required', 'string', 'max:12'],
                ]);

                if ($check_validation_sku->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation_sku->errors(),
                    ], 422);
                } else {
                    $check_sku = Product::where('sku', $request->sku)->first();

                    if ($check_sku) {
                        return response()->json([
                            'status' => false,
                            'message' => "Product (SKU) already exists!",
                        ], 422);
                    } else {
                        $check_validation = Validator::make($request->all(), [
                            'name_product' => ['required', 'string', 'max:255'],
                            'category' => ['required', 'string', 'max:255'],
                            'price' => ['required', 'numeric', 'max:999999999.99'],
                            'image' => ['required', 'string', 'max:255'],
                            'rating' => ['required', 'numeric', 'max:5.00'],
                            'feature_1' => ['nullable', 'string', 'max:255'],
                            'feature_2' => ['nullable', 'string', 'max:255'],
                            'feature_3' => ['nullable', 'string', 'max:255'],
                            'feature_4' => ['nullable', 'string', 'max:255'],
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
                                "sold" => 0,
                            ]);

                            $product_details = ProductDetail::create([
                                "name_product" => $request->name_product,
                                "sku" => $request->sku,
                                "feature_1" => $request->feature_1,
                                "feature_2" => $request->feature_2,
                                "feature_3" => $request->feature_3,
                                "feature_4" => $request->feature_4,
                            ]);

                            return response()->json([
                                'status' => true,
                                'message' => "Product Created successfully!",
                                'product' => $products,
                                'product_detail' => $product_details
                            ], 200);
                        }
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $products = Product::select('*')
                    ->join('product_details', 'products.id', '=', 'product_details.id')
                    ->get();

                if ($products) {
                    return response()->json([
                        'status' => true,
                        'message' => "Show All Products!",
                        'products' => $products
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Product not found!',
                    ], 422);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
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
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $check_validation_product_id = Validator::make($request->route()->parameters(), [
                    'id' => ['required', 'integer'],
                ]);

                if ($check_validation_product_id->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation_product_id->errors(),
                    ], 422);
                } else {
                    $check_avail = Product::find($request->id);

                    if ($check_avail) {
                        $check_validation = Validator::make($request->all(), [
                            'name_product' => ['string', 'max:255'],
                            'category' => ['string', 'max:255'],
                            'sku' => ['string', 'max:12'],
                            'price' => ['numeric', 'max:999999999.99'],
                            'image' => ['string', 'max:255'],
                            'rating' => ['numeric', 'max:5.00'],
                            'sold' => ['integer'],
                            'feature_1' => ['string', 'max:255'],
                            'feature_2' => ['string', 'max:255'],
                            'feature_3' => ['string', 'max:255'],
                            'feature_4' => ['string', 'max:255'],
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

                            $product_details = ProductDetail::where('id', $request->id)
                                ->update([
                                    "name_product" => $request->name_product,
                                    "sku" => $request->sku,
                                    "feature_1" => $request->feature_1,
                                    "feature_2" => $request->feature_2,
                                    "feature_3" => $request->feature_3,
                                    "feature_4" => $request->feature_4,
                                ]);

                            return response()->json([
                                'status' => true,
                                'message' => "Product Update successfully!",
                                'product' => $product,
                                'product_detail' => $product_details
                            ], 200);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Product not Found!",
                        ], 404);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $check_validation_product_id = Validator::make($request->route()->parameters(), [
                    'id' => ['required', 'integer'],
                ]);

                if ($check_validation_product_id->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation_product_id->errors(),
                    ], 422);
                } else {
                    $check_avail = Product::find($request->id);

                    if ($check_avail) {
                        $product = Product::where('id', $request->id)
                            ->delete();

                        $product_detail = ProductDetail::where('id', $request->id)
                            ->delete();

                        return response()->json([
                            'status' => true,
                            'message' => "Product Deleted successfully!",
                            'product' => $product,
                            'product_detail' => $product_detail
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Product not Found!",
                        ], 404);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }
}
