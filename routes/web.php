<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});


// عرض صفحة إنشاء الحساب
Route::get('/createaccount', [UserController::class, 'create'])->name('createaccount.create');

// تخزين بيانات الحساب الجديد
Route::post('/createaccount', [UserController::class, 'store'])->name('register.store');



Route::get('/login', function () {
    return view( 'user.login');
});
Route::post('/login',[AuthController::class,'login']);


Route::get('/Dashborad', function () {
    return view( 'Dashboard');
});

Route::get('/products', function () {
    return view( 'products');
});

Route::get('/Categories', function () {
    return view( 'Categories');
});

Route::get('/index', function () {
    return view( 'index');
});

Route::get('/index', [UserController::class, 'index'])->name('users.index');
