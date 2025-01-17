<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;


class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('user_logged_in') && !$request->is('login') && !$request->is('login_Validation')) {
            Session::flush();
            return redirect('/login')->with('error', 'Please log in first.');
        }

        if (($request->is('login') || $request->is('login_Validation')) && $request->session()->has('user_logged_in')) {
            return redirect('/Home');
        }
        return $next($request);
    }
}
