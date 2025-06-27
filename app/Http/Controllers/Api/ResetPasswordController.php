<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Password\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request) {
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET ?
            ApiResponse::success(null, __($status), 200):
            ApiResponse::error("An unexpected error occured while resetting the password", __($status), 400);
    }
}
