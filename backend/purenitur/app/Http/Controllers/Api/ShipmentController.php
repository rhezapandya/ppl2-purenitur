<?php

namespace App\Http\Controllers\Api;

use App\Models\Shipment;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Create shipment of orders
     */
    public function create_shipment(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;
            $check_validation = Validator::make($request->all(), [
                'order_id' => ['required', 'integer'],
                'shipment_status' => ['required', 'string', 'max:255', 'in:SHIPPING'],
            ]);

            if ($check_validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation not fulfilled!',
                    'errors' => $check_validation->errors(),
                ], 422);
            } else {
                $orders = Order::where('id', $request->order_id)->first(['id', 'status_payment', 'shipment_id', 'shipment_status']);

                if (!$orders) {
                    return response()->json([
                        'status' => false,
                        'message' => "Order ID not Exist!",
                    ], 404);
                } else if ($orders->status_payment !== 'CONFIRMED') {
                    return response()->json([
                        'status' => false,
                        'message' => "Payment not Confirmed yet!",
                    ], 404);
                } else {
                    $update_order = Order::where('id', $request->order_id)
                        ->update(['shipment_id' => $request->order_id, 'shipment_status' => $request->shipment_status]);

                    return response()->json([
                        'status' => true,
                        'message' => "Shipment Created. Status: Shipping. Order Updated!",
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_shipment(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;
            $check_validation = Validator::make($request->all(), [
                'order_id' => ['required', 'integer'],
                'shipment_status' => ['required', 'string', 'max:255', 'in:ARRIVED'],
            ]);

            if ($check_validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation not fulfilled!',
                    'errors' => $check_validation->errors(),
                ], 422);
            } else {
                $orders = Order::where('id', $request->order_id)->first(['id', 'shipment_id', 'shipment_status']);

                if (!$orders) {
                    return response()->json([
                        'status' => false,
                        'message' => "Order ID not Exist!",
                    ], 404);
                } else if ($orders->shipment_status === 'ARRIVED') {
                    return response()->json([
                        'status' => false,
                        'message' => "Your order already arrived at destination",
                    ], 404);
                } else {
                    $update_order = Order::where('id', $request->order_id)
                        ->update(['shipment_id' => $request->order_id, 'shipment_status' => $request->shipment_status]);

                    return response()->json([
                        'status' => true,
                        'message' => "Shipment Arrived. Order Updated!",
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        //
    }
}
