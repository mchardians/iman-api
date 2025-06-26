<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSimpleResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="UserPayload",
     *     type="object",
     *     required={
     *         "id", "code", "name", "name_upper", "email", "photo",
     *         "role", "created_at", "created_at_human"
     *     },
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="code", type="string"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="name_upper", type="string"),
     *     @OA\Property(property="email", type="string", format="email"),
     *     @OA\Property(property="photo", type="string"),
     *     @OA\Property(property="role", ref="#/components/schemas/RolePayload"),
     *     @OA\Property(property="created_at", type="string"),
     *     @OA\Property(property="created_at_human", type="string"),
     *     example={
     *         "id": 1,
     *         "code": "USR/2506/0001",
     *         "name": "John Doe",
     *         "name_upper": "JOHN DOE",
     *         "email": "john@example.com",
     *         "photo": "https://example.com/images/john.jpg",
     *         "role": {
     *             "id": 1,
     *             "role_code": "ROL/2506/0001",
     *             "name": "jamaah-umum",
     *             "created_at": "25 Juni 2025 14:30",
     *             "created_at_human": "2 jam yang lalu"
     *         },
     *         "created_at": "25 Juni 2025 14:30",
     *         "created_at_human": "2 jam yang lalu"
     *     }
     * )
     */
    public function toArray(Request $request): array
    {
        Carbon::setLocale("id");

        return [
            "id" => $this->id,
            "code" => $this->user_code,
            "name" => $this->name,
            "name_upper" => ucwords($this->name),
            "email" => $this->email,
            "photo" => $this->photo ? asset($this->photo) : null,
            "role" => new RoleSimpleResource($this->role),
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i"),
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
