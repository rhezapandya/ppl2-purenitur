<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => 'auth:sanctum'], function () {
// });

// API For FE
Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/catalog/{category}', [CatalogController::class, 'index_filtered']);
Route::get('/product-detail/{product:name_product}', [CatalogController::class, 'show']);
Route::get('/cart', [CartController::class, 'show_cart']);
Route::post('/cart/add', [CartController::class, 'store']);
Route::post('/cart/add-qty', [CartController::class, 'increaseQuantity']);
Route::post('/cart/dec-qty', [CartController::class, 'decreaseQuantity']);

Route::get('/checkout', [OrderController::class, 'index']);
Route::post('/checkout/create', [OrderController::class, 'store']);

// API For Products CRUD (Admin)
Route::get('/admin/products', [ProductController::class, 'show']);
Route::post('/admin/products/create', [ProductController::class, 'store']);
Route::patch('/admin/products/update/{id}', [ProductController::class, 'update']);
Route::delete('/admin/products/delete/{id}', [ProductController::class, 'destroy']);

// API For Users CRUD
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::patch('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::post("login", [UserController::class, 'login']);
