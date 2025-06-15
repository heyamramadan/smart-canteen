<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ParentAuthController;

Route::post('/login-parent', [ParentAuthController::class, 'login']);
