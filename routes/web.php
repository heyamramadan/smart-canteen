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
  use App\Http\Controllers\Auth\ArchivedUserController;
use App\Http\Controllers\Auth\ArchivedStudentController;
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
//تسجيل دخول
Route::post('/login', [AuthController::class, 'login']);
//تسجيل خروج
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ✅ مسارات المسؤولين
Route::middleware(['auth', 'admin'])->group(function () {

    // إدارة المستخدمين
 Route::get('/index', [UserController::class, 'index'])->name('users.index');
 Route::post('/index', [UserController::class, 'store'])->name('users.store');
 Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
//Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
///ارشيف

Route::get('/archived-users', [ArchivedUserController::class, 'index'])->name('archived-users.index');
Route::post('/archived-users/{id}/restore', [ArchivedUserController::class, 'restore'])->name('archived-users.restore');
Route::get('/archived-users/search', [ArchivedUserController::class, 'searchArchived']);
    // إدارة الطلاب
 Route::get('/students', [StudentController::class, 'index'])->name('students.index');
   // Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
// Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::get('/students/{student}/pincode', [StudentController::class, 'showPinCode']);
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');


Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');

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

    // إدارة الطلاب

Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');
Route::get('/students/{student}/pincode', [StudentController::class, 'showPinCode']);
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

Route::get('/transactions/search', [TransactionController::class, 'search'])->name('transactions.search');

Route::post('/archived-users/{id}/restore', [ArchivedUserController::class, 'restore'])->name('archived-users.restore');

///ارشيف


Route::get('/archived-users', [ArchivedUserController::class, 'index'])->name('archived-users.index');
Route::post('/archived-users/{id}/restore', [ArchivedUserController::class, 'restore'])->name('archived-users.restore');
Route::get('/archived-users/search', [ArchivedUserController::class, 'searchArchived']);

///ارشيف طلاب

Route::get('/archived-students', [ArchivedStudentController::class, 'index'])->name('archived-students.index');
Route::post('/archived-students/{id}/restore', [ArchivedStudentController::class, 'restore'])->name('archived-students.restore');
Route::get('/archived-students/search', [ArchivedStudentController::class, 'search'])->name('archived-students.search');

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


// ✅ التقرير اليومي (Daily Report) للموظف
    Route::get('/daily-report', [DailyReportController::class, 'index'])->name('daily.report');


});
