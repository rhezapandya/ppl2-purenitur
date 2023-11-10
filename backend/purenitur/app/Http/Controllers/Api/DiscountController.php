<?php

namespace App\Http\Controllers\Api;

use App\Models\Discount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();

        if ($discounts) {
            return response()->json([
                'status' => true,
                'message' => "Show All Discounts!",
                'discounts' => $discounts
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Discount not found!',
            ], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $check_discount = Discount::where('name_discount', $request->discount_name)->first();

        if ($check_discount) {
            return response()->json([
                'status' => false,
                'message' => "Discount already exists!",
            ], 422);
        } else {
            $check_validation = Validator::make($request->all(), [
                'name_discount' => ['string', 'max:255'],
                'percentage' => ['integer', 'max:100'],
            ]);

            if ($check_validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation not fulfilled!',
                    'errors' => $check_validation->errors(),
                ], 422);
            } else {
                $discounts = Discount::create([
                    "name_discount" => $request->name_discount,
                    "percentage" => $request->percentage,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => "Discount Created successfully!",
                    'discount' => $discounts,
                ], 200);
            }
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
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $check_avail = Discount::find($request->discount_id);

        if ($check_avail) {
            $check_validation = Validator::make($request->all(), [
                'name_discount' => ['string', 'max:255'],
                'percentage' => ['integer', 'max:100'],
            ]);

            if ($check_validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation not fulfilled!',
                    'errors' => $check_validation->errors(),
                ], 422);
            } else {
                $discount = Discount::where('id', $request->discount_id)
                    ->update([
                        'name_discount' => ['string', 'max:255'],
                        'percentage' => ['integer', 'max:100'],
                    ]);

                return response()->json([
                    'status' => true,
                    'message' => "Discount Update successfully!",
                    'discount' => $discount,
                ], 200);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => "Discount Unavailable!",
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $check_avail = Discount::find($request->discount_id);

        if ($check_avail) {
            $discount = Discount::where('id', $request->discount_id)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => "Discount Deleted successfully!",
                'discount' => $discount,
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Discount Unavailable!",
            ], 200);
        }
    }
}
