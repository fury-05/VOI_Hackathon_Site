<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_admin) { // Check if user is logged in and is_admin is true
            return $next($request);
        }

        // If not admin, redirect to home or show an unauthorized error
        // You can customize this (e.g., abort(403, 'Unauthorized action.');)
        return redirect('/')->with('error', 'You do not have administrative access.');
    }
}
