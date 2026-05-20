<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Hrlogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('hr')->check() && Auth::guard('hr')->user()->role === 'hr') {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Access denied. Logged-in users only.');
    }
}
