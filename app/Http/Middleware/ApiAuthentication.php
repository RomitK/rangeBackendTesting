<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthentication
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
        $routePath = $request->path();
      
        if (strpos($routePath, 'api/teams') !== false ){
            return $next($request);
        }
        $token = $request->header('authorization-token');
        if ($token != "3MPHJP0BC63435345342") {
            return response()->json(['message'=>'unauthenticated'],401);
        }
        return $next($request);
    }
}
