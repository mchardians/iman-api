<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request) {
        $request->validate([
            "email" => "required|email:rfc,dns|exists:users,email"
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ?
            response()->json(["message" => __($status)], 200):
            response()->json(["message" => __($status)], 400);
    }
}
