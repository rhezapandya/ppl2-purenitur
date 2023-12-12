<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// API For Account Management (User)
Route::post("login", [UserController::class, 'login']);
Route::post("register", [UserController::class, 'register']);
Route::post("logout", [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get("profile", [UserController::class, 'profile'])->middleware('auth:sanctum');
Route::post("profile-update", [UserController::class, 'profile_update'])->middleware('auth:sanctum');
Route::post("password-update", [UserController::class, 'password_update'])->middleware('auth:sanctum');

// API For Catalog & Product View (User)
Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/catalog/{category}', [CatalogController::class, 'index_filtered']);
Route::get('/product-detail/{name_product}', [CatalogController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // API For Shopping Cart (User)
    Route::get('/cart', [CartController::class, 'show_cart']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::post('/cart/add-qty', [CartController::class, 'increaseQuantity']);
    Route::post('/cart/dec-qty', [CartController::class, 'decreaseQuantity']);

    // API For Checkout & Payment (User)
    Route::get('/checkout', [OrderController::class, 'index']);
    Route::post('/checkout/create', [OrderController::class, 'store']);
    Route::post('/checkout/payment', [OrderController::class, 'transaction_store']);
    Route::post('/checkout/payment/confirm', [OrderController::class, 'confirm_transaction']);
    Route::post('/checkout/payment/failure', [OrderController::class, 'failed_transaction']);
    Route::post('/checkout/shipment/create', [ShipmentController::class, 'create_shipment']);
    Route::post('/checkout/shipment/update', [ShipmentController::class, 'update_shipment']);

    // API For Products CRUD (Admin)
    Route::get('/admin/products', [ProductController::class, 'show']);
    Route::post('/admin/products/create', [ProductController::class, 'store']);
    Route::patch('/admin/products/update/{id}', [ProductController::class, 'update']);
    Route::delete('/admin/products/delete/{id}', [ProductController::class, 'destroy']);

    // API For Discount CRUD (Admin)
    Route::get('/admin/discount', [DiscountController::class, 'index']);
    Route::post('/admin/discount/create', [DiscountController::class, 'create']);
    Route::patch('/admin/discount/update/{id}', [DiscountController::class, 'update']);
    Route::delete('/admin/discount/delete/{id}', [DiscountController::class, 'destroy']);

    // API For Users CRUD (Admin)
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
