<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentController;
use App\Http\Controllers\Auth\CategoryController;
use App\Http\Controllers\Auth\ProductController;
use App\Http\Controllers\Auth\WalletController;
use App\Http\Controllers\Auth\PointController;
use Illuminate\Support\Facades\Route;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('home');
});

// تسجيل الدخول
Route::get('/login', function () {
    return view('user.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// ✅ مسارات المسؤولين
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('Dashboard');
    });
//منتجات
  Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

//تصنيفات
      Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');

    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    // إدارة المستخدمين
    Route::get('/index', [UserController::class, 'index'])->name('users.index');
    Route::post('/index', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // إدارة الطلاب
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    //جلب تصنيفات غير ممنوعة
Route::get('/students/{id}/allowed-categories', [CategoryController::class, 'getCategoriesForStudent']);


     //محافظة

Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
Route::post('/wallet/charge', [WalletController::class, 'charge'])->name('wallet.charge');

//مبيعات
Route::get('/point', [PointController::class, 'index'])->name('point');

});



// ✅ مسارات الموظفين

Route::middleware(['auth', 'adminOrEmployee'])->group(function () {
    //مبيعات
    Route::get('/point', [PointController::class, 'index'])->name('point');

});


    // أضف مسارات أخرى للموظف إذا لزم

