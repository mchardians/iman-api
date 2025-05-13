<?php

namespace App\Services;

use Carbon\CarbonInterval;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function authenticate($credentials) {
        if (! $token = auth()->attempt($credentials)) {
            throw new HttpException(401, "Unauthorized.");
        }

        $ttl = auth()->factory()->getTTL() * 60;

        return [
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => $ttl,
            "expires_in_human" => CarbonInterval::seconds($ttl)->cascade()->forHumans([
                "parts" => 3,
                "short" => true,
                "join" => true,
            ])
        ];
    }

    public function getAuthenticatedUser() {
        if(!auth()->check()) {
            throw new HttpException(401, "Unauthenticated");
        }

        return auth()->user();
    }

    public function logout() {
        $user = $this->getAuthenticatedUser();

        auth()->logout();

        return $user;
    }

    public function getRefreshToken() {
        return auth()->refresh();
    }
}