<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
    }

    public function show_cart(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns,email',
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $carts = ShoppingCart::where('email_user', $request->email)->first();

            if ($carts == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart not found!',
                ], 422);
            } else {
                $cartData = json_decode($carts->cart, true);

                return response()->json([
                    'status' => true,
                    'message' => $carts->username_user . "'s Shopping Cart",
                    'carts' => $cartData
                ], 200);
            }
        }
    }

    public function store(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns,email',
            'item_id' => ['integer'],
            'name_product' => ['string', 'max:255'],
            'price' => ['numeric', 'max:999999999.99'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $carts = ShoppingCart::where('email_user', $request->email)->first();

            if ($carts) {
                $cartData = json_decode($carts->cart, true);

                if (isset($cartData[1]['item_id']) && $cartData[1]['item_id'] == $request->item_id) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Item with the same item_id is already in the cart.',
                    ], 400);
                }

                // Calculate the next 'rowId'
                $lastRowId = empty($cartData) ? 1 : max(array_keys($cartData)) + 1;

                $item = [
                    $lastRowId => [
                        "rowId" => $lastRowId,
                        "item_id" => $request->item_id,
                        "name_product" => $request->name_product,
                        "qty" => 1,
                        "price" => number_format($request->price, 2),
                        "tax" => number_format(($request->price / 10), 2),
                        "subtotal" => number_format($request->price + ($request->price / 10), 2),
                    ]
                ];

                $cartData[$lastRowId] = $item[$lastRowId];

                // Encode the updated cart data back to JSON
                $carts->cart = json_encode($cartData);

                // Save the updated cart
                $carts->save();

                return response()->json([
                    'status' => true,
                    'message' => "Shopping cart updated successfully.",
                    'carts' => $carts,
                ], 200);
            } else {
                // If the cart doesn't exist, create a new cart with the first item
                $item = [
                    1 => [
                        "rowId" => 1,
                        "item_id" => $request->item_id,
                        "name_product" => $request->name_product,
                        "qty" => 1,
                        "price" => number_format($request->price, 2),
                        "tax" => number_format(($request->price / 10), 2),
                        "subtotal" => number_format($request->price + ($request->price / 10), 2),
                    ]
                ];

                $cart = new ShoppingCart();
                $cart->email_user = $request->email;
                $cart->cart = json_encode($item);
                $cart->save();

                return response()->json([
                    'status' => true,
                    'message' => "Shopping cart created and updated successfully.",
                    'carts' => $cart,
                ], 200);
            }
        }
    }

    public function increaseQuantity(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns,email',
            'rowId' => ['integer'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $carts = ShoppingCart::where('email_user', $request->email)->first();
            $item_decode = json_decode($carts->cart, true);
            $requestedRowId = $request->rowId;

            if (isset($item_decode[$requestedRowId])) {
                $item = $item_decode[$requestedRowId];
                $qty = $item['qty'] + 1;
                $item['price'] = str_replace(',', '', $item['price']);

                // Update the 'qty' field and recalculate 'price', 'tax', and 'subtotal'
                $item['qty'] = $qty;
                $item['price'] = (floatval($item['price']) / ($qty - 1)) * $qty;
                $item['tax'] = number_format((((intval($item['price']) / $item['qty']) * $qty) / 10), 2);
                $item['subtotal'] = number_format(floatval($item['price']) + (floatval($item['price']) / 10), 2);
                $item['price'] = number_format($item['price'], 2);

                $item_update = [
                    $requestedRowId => $item
                ];

                // Update the cart data with the modified item
                $item_decode[$requestedRowId] = $item;

                $update = ShoppingCart::where('email_user', $request->email)
                    ->update(['cart' => $item_decode]);

                return response()->json([
                    'status' => true,
                    'message' => "Quantity Increased!",
                    'carts' => $update,
                    'update' => $item_update,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Item with rowId $requestedRowId not found in the cart",
                ], 404);
            }
        }
    }

    public function decreaseQuantity(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns,email',
            'rowId' => ['integer'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $carts = ShoppingCart::where('email_user', $request->email)->first();
            $item_decode = json_decode($carts->cart, true);
            $requestedRowId = $request->rowId;

            if (isset($item_decode[$requestedRowId])) {
                $item = $item_decode[$requestedRowId];
                $qty = $item['qty'] - 1;
                $item['price'] = str_replace(',', '', $item['price']);

                // Ensure the quantity doesn't go below zero
                if ($qty > 0) {
                    $item['qty'] = $qty;

                    // Update 'price,' 'tax,' and 'subtotal' accordingly
                    $item['price'] = (floatval($item['price']) / ($qty + 1)) * $qty;
                    $item['tax'] = number_format((((intval($item['price']) / $item['qty']) * $qty) / 10), 2);
                    $item['subtotal'] = number_format(floatval($item['price']) + (floatval($item['price']) / 10), 2);
                    $item['price'] = number_format($item['price'], 2);

                    $item_update = [
                        $requestedRowId => $item
                    ];

                    // Update the cart data with the modified item
                    $item_decode[$requestedRowId] = $item;

                    $update = ShoppingCart::where('email_user', $request->email)
                        ->update(['cart' => $item_decode]);

                    return response()->json([
                        'status' => true,
                        'message' => "Quantity Decreased!",
                        'carts' => $update,
                        'update' => $item_update,
                    ], 200);
                } else {
                    unset($item_decode[$requestedRowId]);

                    $update = ShoppingCart::where('email_user', $request->email)
                        ->update(['cart' => $item_decode]);

                    return response()->json([
                        'status' => false,
                        'message' => "Item Deleted.",
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Item with rowId $requestedRowId not found in the cart",
                ], 404);
            }
        }
    }
}
