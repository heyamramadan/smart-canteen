<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
        {
        if (Auth::check() && Auth::user()->role === 'مسؤول') {
            return $next($request);
        }

        return redirect('/login')->withErrors(['unauthorized' => 'هذه الصفحة متاحة فقط للمسؤولين']);
    }

}
