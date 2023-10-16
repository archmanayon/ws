<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScholarsOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if(auth()->user()->role_id === 4){ 
            
        //     return redirect('dashboard'); 
            
        // }

        if(auth()->user()->username != 'abmanayon'){
            if(auth()->user()->role_id != 1){
                
                return redirect('dashboard');
            } 
        }


        return $next($request);
    }
}
