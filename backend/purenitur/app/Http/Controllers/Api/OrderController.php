<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\Discount;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'id' => 'integer',
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $check_id = User::where('id', $request->id)
                ->first();

            if (!$check_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have not registered yet!',
                ], 422);
            } else {
                $email = Order::where('orders.user_id', $request->id)
                    ->join('users', 'users.id', '=', 'orders.user_id')
                    ->first('users.username');

                if (!$email) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You have no orders!',
                    ], 422);
                } else {

                    $orders = Order::where('orders.user_id', $request->id)
                        ->join('users', 'users.id', '=', 'orders.user_id')
                        ->get([
                            'orders.id',
                            'orders.user_id',
                            'orders.cart',
                            'orders.price_total',
                            'orders.discount_id',
                            'orders.final_price',
                            'orders.transaction_id',
                            'orders.payment_type',
                            'orders.status_payment',
                            'orders.image_payment',
                            'orders.shipment_id',
                            'orders.shipment_status',
                            'orders.shipment_address',
                            'orders.created_at',
                            'orders.updated_at',
                        ]);

                    if ($orders) {
                        return response()->json([
                            'status' => true,
                            'message' => $email->username . "'s Order",
                            'orders' => $orders
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Order Empty",
                        ], 404);
                    }
                }
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
        $check_validation = Validator::make($request->all(), [
            'id' => 'integer',
            'discount_name' => ['nullable', 'string', 'max:255'],
            "payment_method" => ['string', 'max:255'],
            "shipment_address" => ['string', 'max:255'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $users = User::where('id', $request->id)
                ->first(['id', 'email']);

            if (!$users) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have not registered yet!',
                ], 422);
            } else {

                $carts = ShoppingCart::where('email_user', $users->email)
                    ->first(['cart']);

                $item_in_carts = json_decode($carts->cart, true);

                if ($item_in_carts == null) {
                    return response()->json([
                        'status' => true,
                        'message' => "Carts Empty",
                        'user' => $users,
                    ], 404);
                } else {
                    $base_price = 0;

                    foreach ($item_in_carts as $item) {
                        $base_price += floatval(str_replace(',', '', $item['subtotal']));
                    }

                    $discount = Discount::where('name_discount', $request->discount_name)
                        ->first(['name_discount', 'percentage']);

                    $final_price = $base_price;

                    if ($discount) {
                        $discount_price = (intval($discount->percentage) * $base_price) / 100;
                        $final_price = $base_price - $discount_price;
                        $discount_name = $request->discount_name;
                    } else {
                        $discount_name = null;
                    }

                    $base_price = str_replace(',', '', number_format($base_price, 2));
                    $final_price = str_replace(',', '', number_format($final_price, 2));
                    $final_item = json_encode($item_in_carts);

                    $createOrder = array(
                        "user_id" => $request->id,
                        "cart" => $final_item,
                        "price_total" => $base_price,
                        "discount_id" => $discount_name,
                        "final_price" => $final_price,
                        "payment_type" => $request->payment_method,
                        "shipment_address" => $request->shipment_address,
                    );

                    Order::create($createOrder);

                    $truncate_cart = ShoppingCart::where('email_user', $users->email)
                        ->update(['cart' => '[]']);

                    return response()->json([
                        'status' => true,
                        'message' => "Order Created",
                        'carts' => $item_in_carts,
                        'user' => $users,
                        'createOrder' => $createOrder
                    ], 200);
                }
            }
        }
    }

    /**
     * Store a newly create transaction on update order.
     */
    public function transaction_store(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'order_id' => 'integer',
            'image_payment' => 'required'
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $orders = Order::where('id', $request->order_id)->first(['cart']);

            if (!$orders) {
                return response()->json([
                    'status' => true,
                    'message' => "Order ID not Exist!",
                ], 404);
            } else {
                $isTransaction = Order::where('id', $request->order_id)->first(['transaction_id']);

                if (!$isTransaction->transaction_id) {

                    $cartItem = json_decode($orders->cart);

                    foreach ($cartItem as $data) {
                        $product_array[] = $data->item_id;
                    }

                    for ($i = 0; $i < sizeof($product_array); $i++) {
                        $param1 = Product::where('id', $product_array[$i])->get('sold');
                        $param2 = Product::where('id', $product_array[$i])
                            ->update([
                                'sold' => $param1[0]->sold + 1,
                            ]);
                    }

                    $validatedData  = $request->validate([
                        'image_payment' => 'required'
                    ]);

                    $validatedData['transaction_id'] = $request->order_id;
                    $validatedData['status_payment'] = "WAITING FOR CONFIRMATION";
                    $validatedData['image_payment'] = $request->image_payment;
                    $validatedData['updated_at'] = Carbon::now()->setTimezone('Asia/Jakarta');

                    if ($request->file('image_payment')) {
                        $validatedData['image_payment'] = $request->file('image_payment')->store('img_payment');

                        $imageName = $validatedData['image_payment'];

                        $request->image_payment->move(public_path('img_payment'), $imageName);
                    }

                    $update = Order::where('id', $request->order_id)
                        ->update($validatedData);

                    return response()->json([
                        'status' => true,
                        'message' => "Transaction success. Order updated!",
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "You have done your transaction already!",
                        'transaction_id' => $isTransaction->transaction_id
                    ], 404);
                }
            }
        }
    }

    /**
     * Update Transaction (Confirmation)
     */
    public function confirm_transaction(Request $request)
    {
        $check_validation = Validator::make($request->all(), [
            'order_id' => 'integer',
            'status_payment' => ['string', 'max:255'],
        ]);

        if ($check_validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation->errors(),
            ], 422);
        } else {
            $orders = Order::where('id', $request->order_id)->first(['id', 'status_payment', 'cart']);

            if (!$orders) {
                return response()->json([
                    'status' => true,
                    'message' => "Order ID not Exist!",
                ], 404);
            } else {
                $update_order = Order::where('id', $request->order_id)
                    ->update(['status_payment' => $request->status_payment]);

                return response()->json([
                    'status' => true,
                    'message' => "Transaction Payment Confirmation Success. Order Updated!",
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
