<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request) {
        $request->validate([
            "email" => "required|email:rfc,dns"
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ?
            ApiResponse::success(null, __($status), 200):
            ApiResponse::error("An unexpected error occured while sending forgot password request to your email!", __($status), 400);
    }
}
