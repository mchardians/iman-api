<?php

namespace App\Services;

use App\Libraries\CodeGeneration;
use App\Models\User;
use App\Repositories\Contracts\UserContract;

class RegisterService
{
    private CodeGeneration $userCode;
    public function __construct(protected UserContract $userRepository) {
        $this->userRepository = $userRepository;
        $this->userCode = new CodeGeneration(User::class, "user_code", "USR");
    }

    public function register(array $data) {

        return $this->userRepository->create([
            "user_code" => $this->getUserCode(),
            ...$data,
            "role_id" => 6
        ]);
    }

    private function getUserCode(): string {
        return $this->userCode->getGeneratedCode();
    }
}