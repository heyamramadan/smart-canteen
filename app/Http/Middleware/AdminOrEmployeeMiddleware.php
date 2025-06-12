<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOrEmployeeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array(Auth::user()->role, ['مسؤول', 'موظف'])) {
            return $next($request);
        }

        return redirect('/login')->withErrors(['unauthorized' => 'غير مصرح لك بدخول هذه الصفحة.']);
    }
}
