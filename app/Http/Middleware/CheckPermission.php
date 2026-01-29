<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->hasRole('OWNER')) {
            return $next($request);
        }

        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden: You do not have permission to perform this action.'], 403);
    }
}
