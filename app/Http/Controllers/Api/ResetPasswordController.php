<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function reset(Request $request) {
        $request->validate([
            "token" => "required",
            "email" => "required|email:rfc,dns",
            "password" => "required|min:8|confirmed"
        ]);

        $status = Password::reset(
            $request->only("email", "password", "password_confirmation", "token"),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET ?
            response()->json(["message" => __($status)], 200):
            response()->json(["message" => __($status)], 400);
    }
}
