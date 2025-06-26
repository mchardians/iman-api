<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     required={"auth", "user"},
 *     @OA\Property(property="auth", ref="#/components/schemas/AuthPayload"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserPayload"),
 *     example={
 *         "auth": {
 *             "access_token": "blablabla...",
 *             "token_type": "bearer",
 *             "expires_in": "900",
 *             "expires_in_human": "15mnt"
 *         },
 *         "user": {
 *             "id": 1,
 *             "code": "USR001",
 *             "name": "John Doe",
 *             "name_upper": "JOHN DOE",
 *             "email": "john@example.com",
 *             "photo": "https://example.com/images/john.jpg",
 *             "role": {
 *                 "id": 1,
 *                 "role_code": "ROL/2506/0001",
 *                 "name": "jamaah-umum",
 *                 "created_at": "25 Juni 2025 14:30",
 *                 "created_at_human": "2 jam yang lalu"
 *             },
 *             "created_at": "25 Juni 2025 14:30",
 *             "created_at_human": "2 jam yang lalu"
 *         }
 *     }
 * )
 */

class TokenSimpleResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="AuthPayload",
     *     type="object",
     *     required={"access_token", "token_type", "expires_in", "expires_in_human"},
     *     @OA\Property(property="access_token", type="string", example="eyJhbGciOi..."),
     *     @OA\Property(property="token_type", type="string", example="bearer"),
     *     @OA\Property(property="expires_in", type="integer", example=3600),
     *     @OA\Property(property="expires_in_human", type="string", example="1h"),
     *     example={
     *         "access_token": "blablabla...",
     *         "token_type": "bearer",
     *         "expires_in": "900",
     *         "expires_in_human": "15mnt"
     *     }
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            "auth" => [
                "access_token" => $this["token"],
                "token_type" => $this["type"],
                "expires_in" => $this["ttl"],
                "expires_in_human" => CarbonInterval::seconds($this["ttl"])->cascade()->forHumans([
                    "parts" => 3,
                    "short" => true,
                    "join" => true,
                ])
            ],
            "user" => new UserSimpleResource(auth()->setToken($this["token"])->user())
        ];
    }
}
