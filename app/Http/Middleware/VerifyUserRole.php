<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        try {
            $user = auth()->user();

            if(!$user) {
                throw new HttpException(401, "Unauthorized");
            }

            $allowedRoles = explode('|', $role);

            if(!in_array($user->role->name, $allowedRoles)) {
                throw new HttpException(403, "Forbidden");
            }

            return $next($request);
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Request denied! ensure you have logged in and have a valid permission.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }

    }
}
