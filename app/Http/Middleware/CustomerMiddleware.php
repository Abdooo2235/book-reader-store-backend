<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->status == 'block') {
            return response()->json([
                'message' => 'your account is blocked'
            ], 401);
        }
        if ($request->user()->type == 'customer') {
            return $next($request);
        }
        return response()->json([
            'message' => 'you are not a customer'
        ], 401);
    }
}
