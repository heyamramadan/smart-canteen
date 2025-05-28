<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});


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

Route::get('/index', action: [UserController::class, 'index'])->name('users.index');
Route::post('/index', [UserController::class, 'store'])->name('users.store');
// Make sure this points to your UserController, not Auth\UserController
Route::get('/users/archive', [UserController::class, 'archive'])->name('users.archive');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');


Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
