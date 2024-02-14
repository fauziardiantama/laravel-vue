<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        //if user's role not any of the roles
        if (!in_array($request->user()->role, $roles)) {
            //return default authentication error
            return response()->json(['message'=>'Unauthorized'], 401);
        }
        return $next($request);
    }
}
