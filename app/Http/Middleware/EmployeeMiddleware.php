<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
     {
        if (Auth::check() && Auth::user()->role === 'موظف') {
            return $next($request);
        }

        return redirect('/login')->withErrors(['unauthorized' => 'غير مصرح لك بدخول هذه الصفحة.']);
    }
}
