<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadsOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->username == 'abmanayon' ||
            auth()->user()->role_id == 4 ||
            auth()->user()->role_id == 5){

                return $next($request);
        }else{
            
            return redirect('dashboard');
        }
    
        
    }
}
