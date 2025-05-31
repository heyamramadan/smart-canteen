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


Route::middleware(['admin'])->group(function () {
    Route::get('/Dashborad', function () {
        return view('Dashboard');
    });


Route::get('/products', function () {
    return view( 'products');
});

Route::get('/Categories', function () {
    return view( 'Categories');
});


//مستخدمين
Route::get('/index', [UserController::class, 'index'])->name('users.index');
Route::post('/index', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');


Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');

});
