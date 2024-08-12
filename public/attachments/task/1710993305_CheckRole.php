<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $roles = explode("|",$roles);

        if(auth()->check() && in_array($request->user()->role,$roles))
        {        
           return $next($request);
        }  
        if($request->wantsJson()) { 
            return response()->json([
                'error' => "You don't have access!"
            ], 402);
        }else{
            return redirect('/')->with('error',"You don't have access!");
        }

        
        //return $next($request);
    }
}