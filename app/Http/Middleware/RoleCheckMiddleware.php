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
    public function handle(Request $request, Closure $next,...$roles): Response
    {
        $user = Auth::user();
// $user->role == $role
        if( $user &&  in_array($user->role, $roles))
        {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => "You can't access this route!"
        ],403);
    }
}
