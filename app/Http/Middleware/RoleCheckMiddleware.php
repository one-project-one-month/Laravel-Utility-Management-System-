<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        $user = Auth::user();

        if( $user &&  $user->role == $role)
        {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => "You can't access this route!"
        ],403);
    }
}
