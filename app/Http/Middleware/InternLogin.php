<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::guard('intern')->check() && Auth::guard('intern')->user()->role === 'intern') {
            return $next($request);
        }
        return redirect()->route('intern.login')->with('error', 'Access denied. Logged-in users only.');
    }
}
