<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleSimpleCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "role_code" => $this->role_code,
            "name" => $this->name,
            "created_at" => $this->created_at->translatedFormat('d F Y'),
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
