<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class IsManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::user() &&  Auth::user()->role == "manager") {
             return $next($request);
        }
        if($request->wantsJson()) { 
            return response()->json([
                'error' => "You have not manager access!"
            ], 402);
        }else{
            return redirect('/')->with('error','You have not manager access');
        }

        
        //return $next($request);
    }
}