<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParentAuthController;
use App\Http\Controllers\Api\ParentStudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::post('/login-parent', [ParentAuthController::class, 'login']);
    Route::get('/parent/students', [ParentStudentController::class, 'getMyStudents']);
