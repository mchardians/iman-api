<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleSimpleResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="RolePayload",
     *     type="object",
     *     required={"id", "role_code", "name", "created_at", "created_at_human"},
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="role_code", type="string"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="created_at", type="string", format="string"),
     *     @OA\Property(property="created_at_human", type="string"),
     *     example={
     *         "id": 1,
     *         "role_code": "ROL/2506/0001",
     *         "name": "jamaah-umum",
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
            "role_code" => $this->role_code,
            "name" => $this->name,
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i"),
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
