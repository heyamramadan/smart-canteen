<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParentAuthController;
use App\Http\Controllers\Api\ParentStudentController;
use App\Http\Controllers\Api\ApiWalletController;
use App\Http\Controllers\Api\ApiBannedProductController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;

// تسجيل دخول
Route::post('/login-parent', [ParentAuthController::class, 'login']);

// المسارات المحمية ب auth:sanctum
Route::middleware('auth:sanctum')->group(function () {

    // جلب بيانات المستخدم
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // طلاب ولي الأمر
    Route::get('/parent/students', [ParentStudentController::class, 'getMyStudents']);

// جلب سقف الشراء اليومي لكل طالب
Route::get('/students/daily-limits', [ApiWalletController::class, 'getStudentsLimits']);

// تحديث سقف الشراء لطالب معين
Route::post('/students/daily-limits/update', [ApiWalletController::class, 'updateStudentDailyLimit']);


// المنتجات والتصنيفات
Route::get('/categories-products', [ProductApiController::class, 'getCategoriesWithProducts']);
Route::get('/products', [ProductApiController::class, 'getAllProducts']);
Route::get('/products/{product_id}', [ProductApiController::class, 'getProduct']);
    // المنتجات الممنوعة
    Route::get('/banned-products', [ApiBannedProductController::class, 'index']);
    Route::post('/banned-products', [ApiBannedProductController::class, 'store']);
    Route::delete('/banned-products/{ban_id}', [ApiBannedProductController::class, 'destroy']);
    ///طلب
Route::get('/parent/orders', [OrderApiController::class, 'getStudentOrders']);
});
