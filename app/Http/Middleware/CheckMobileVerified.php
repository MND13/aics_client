<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMobileVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (auth()->user()->hasRole("user")) {

            if (auth()->user()->mobile_verified) {
                return $next($request);
            } else {
                return response()->view('otp');
            }
        } else {
            return $next($request);
        }
    }
}
