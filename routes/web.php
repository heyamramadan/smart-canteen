<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentController;
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

    Route::get('/products', function () {
        return view('products');
    });

    Route::get('/categories', function () {
        return view('categories');
    });

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
});

// ✅ مسارات الموظفين
Route::middleware(['auth', 'employee'])->group(function () {
    Route::get('/employee-dashboard', function () {
        return view('employee.dashboard');
    });



    // أضف مسارات أخرى للموظف إذا لزم
});
