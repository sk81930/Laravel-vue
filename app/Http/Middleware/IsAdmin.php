<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::user() &&  Auth::user()->role == "admin") {
             return $next($request);
        }
        if($request->wantsJson()) { 
            return response()->json([
                'error' => "You have not admin access!"
            ], 402);
        }else{
            return redirect('/')->with('error','You have not admin access');
        }

        
        //return $next($request);
    }
}