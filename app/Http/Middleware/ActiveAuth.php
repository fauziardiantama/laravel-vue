<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role == "mahasiswa" && $request->user()->mahasiswa()->first()->status != 1) {
            return response()->json(['message'=>'Not active (Unauthorized)'], 401);
        }
        return $next($request);
    }
}
