<?php

use App\Http\Controllers\AdminOwnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SectionController;
use App\Models\AdminOwner;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [AuthController::class, 'login'])->middleware('auth:api');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api', 'CheckRole')->group(function () {

        // Only accessible to users with the 'admin' role
        // section
        Route::apiResource('sections', SectionController::class);
        // category
        Route::apiResource('categories', CategoryController::class);
        //Owner
        Route::apiResource('owners', OwnerController::class);

    });
    Route::middleware('auth:owner-api', 'CheckRole')->group(function () {
    // Only accessible to users with the 'owner' role
    // product
    Route::apiResource('products', ProductController::class);
    Route::put('restaurant', [OwnerController::class,'updateRestaurant']);
    Route::put('main_color', [OwnerController::class,'updateMainColor']);
    Route::put('second_color', [OwnerController::class,'updateSecondColor']);
    Route::put('logo', [OwnerController::class,'updateLogo']);
    Route::get('customers', [OwnerController::class,'customerStatistics']);
    Route::get('sales', [ProductController::class, 'getSalesData']);

});
// Only accessible to users with the 'user' role
//Browse products
Route::get('products', [ProductController::class,'index']);
Route::post('reserve-table/{userId}',[ProductController::class,'reserveTable']);
Route::post('order-products/{mainOrderId}',[ProductController::class,'orderProducts']);
Route::post('rate-product/{userId}',[RatingController::class,'store']);

