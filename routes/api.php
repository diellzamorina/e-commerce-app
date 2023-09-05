<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\checkRole;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // For Admin: Approve/Disapprove Users
    Route::get('/admin/users', [AuthController::class, 'getUsersForApproval']);
    Route::put('/admin/users/{id}/approve', [AuthController::class, 'toggleUserApproval']);
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register'])->middleware('checkRole:admin');

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {
    Route::get('/users', [AuthController::class, 'show']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'checkRole:vendor,admin'])->group(function () {
    Route::post('vendor/products', [VendorController::class, 'addProduct']);
    Route::put('vendor/products/{productId}', [VendorController::class, 'updateProduct']);
    Route::delete('vendor/products/{productId}', [VendorController::class, 'deleteProduct']);
    Route::put('vendor/orders/{orderId}', [VendorController::class, 'updateOrder']);
    Route::post('vendor/orders', [VendorController::class, 'createOrder']);
    Route::delete('vendor/orders/{orderId}', [VendorController::class, 'deleteOrder']); 
    Route::get('vendor/orders', [VendorController::class, 'getVendorOrders']);
    Route::post('/categories', [VendorController::class, 'createCategory']);
});

Route::middleware(['auth:sanctum','checkRole:moderator,admin'])->group(function () {
    Route::post('customer/orders', [CustomerController::class, 'makePurchase']);
    Route::get('customer/products/{productId}', [CustomerController::class, 'getProductDetails']);
    Route::get('customer/product-categories', [CustomerController::class, 'getProductCategories']);
    Route::post('customer/products/{productId}/ratings', [RatingController::class, 'submitRating']); 
    Route::get('customer/ratings', [RatingController::class, 'getUserRatings']);
    Route::get('customer/products/{productId}/ratings', [RatingController::class, 'getProductRatings']); 
    Route::get('/products/search', [CustomerController::class, 'searchProducts']);
    Route::get('/products/filter', [CustomerController::class, 'filterProductsByCategory']);
    Route::get('/products/sort', [CustomerController::class, 'sortProductsByPrice']);

});








