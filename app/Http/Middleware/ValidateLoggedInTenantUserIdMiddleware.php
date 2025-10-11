<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Helpers\ApiResponse;
use App\Models\User;

class ValidateLoggedInTenantUserIdMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = User::where('tenant_id', $request->route('id'))->pluck('id')->first();

        if (auth('sanctum')->user()->id != $userId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        return $next($request);
    }
}
