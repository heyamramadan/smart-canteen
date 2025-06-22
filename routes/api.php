<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParentAuthController;
use App\Http\Controllers\Api\ParentStudentController;
use App\Http\Controllers\Api\ApiWalletController;
use App\Http\Controllers\Api\ApiBannedProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(middleware: 'auth:sanctum');
//تسجيل دخول
Route::post('/login-parent', [ParentAuthController::class, 'login']);
//طلاب
    Route::get('/parent/students', [ParentStudentController::class, 'getMyStudents']);
//
  Route::get('/wallet', [ApiWalletController::class, 'getWallet']);
    Route::post('/wallet/daily-limit', [ApiWalletController::class, 'updateDailyLimit']);

    // المنتجات الممنوعة
    Route::get('/banned-products', [ApiBannedProductController::class, 'index']);
    Route::post('/banned-products', [ApiBannedProductController::class, 'store']);
    Route::delete('/banned-products/{ban_id}', [ApiBannedProductController::class, 'destroy']);
