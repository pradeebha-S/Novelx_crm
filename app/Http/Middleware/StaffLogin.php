<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'staff') {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Access denied. Logged-in users only.');
    }
}
