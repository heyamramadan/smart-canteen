<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParentAuthController;
use App\Http\Controllers\Api\ParentStudentController;
use App\Http\Controllers\Api\ApiWalletController;
use App\Http\Controllers\Api\ApiBannedProductController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;

Route::post('/login-parent', [ParentAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

//
Route::post('/parent/change-password', [ParentAuthController::class, 'changePassword']);
///
Route::get('/parent/students', [ParentStudentController::class, 'getMyStudents']);
///
 Route::get('/students/daily-limits', [ApiWalletController::class, 'getStudentsLimits']);
 Route::post('/students/daily-limits/update', [ApiWalletController::class, 'updateStudentDailyLimit']);
 Route::get('/wallet/balance', [ApiWalletController::class, 'getWalletBalance']);
////

    Route::get('/banned-products', [ApiBannedProductController::class, 'index']);
    Route::post('/banned-products', [ApiBannedProductController::class, 'store']);
    Route::delete('/banned-products/{ban_id}', [ApiBannedProductController::class, 'destroy']);
    Route::delete('/banned-products/by-product', [ApiBannedProductController::class, 'destroyByProduct']);
/////////
 Route::get('/parent/orders', [OrderApiController::class, 'getStudentOrders']);


Route::get('/user', function (Request $request) { return $request->user(); });
Route::get('/parent/top-products', [OrderApiController::class, 'getTopSellingProducts']);

Route::get('/categories-products', [ProductApiController::class, 'getCategoriesWithProducts']);
Route::get('/products', [ProductApiController::class, 'getAllProducts']);
 Route::get('/products/{product_id}', [ProductApiController::class, 'getProduct']);

});
