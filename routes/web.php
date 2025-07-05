<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentController;
use App\Http\Controllers\Auth\CategoryController;
use App\Http\Controllers\Auth\ProductController;
use App\Http\Controllers\Auth\WalletController;
use App\Http\Controllers\Auth\PointController;
use App\Http\Controllers\Auth\OrderController;
use App\Http\Controllers\Auth\InvoiceController;
use App\Http\Controllers\Auth\CardController;
use App\Http\Controllers\Auth\ProfileController;
 use App\Http\Controllers\Auth\ReportController;
 use App\Http\Controllers\Auth\DailyReportController;
  use App\Http\Controllers\Auth\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update'); // تغيير هنا
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');


     //محافظة

Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
Route::post('/wallet/charge', [WalletController::class, 'charge'])->name('wallet.charge');

//يطاقة
Route::get('/cards', [CardController::class, 'index'])->name('students.cards');
Route::get('/students/data', [CardController::class, 'fetch'])->name('students.data');
//تقارير
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/report/export', [ReportController::class, 'export'])->name('report.export');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });



// ✅ مسارات الموظفين

Route::middleware(['auth', 'adminOrEmployee'])->group(function () {
        Route::get('/dashboard', action: [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/point', [OrderController::class, 'create'])->name('point');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('/students/{student_id}/allowed-categories', [StudentController::class, 'getAllowedCategories'])->name('students.allowed-categories');
    //فواتير
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

///
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    ///
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ✅ التقرير اليومي (Daily Report) للموظف
    Route::get('/daily-report', [DailyReportController::class, 'index'])->name('daily.report');


});
