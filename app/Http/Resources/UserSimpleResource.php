<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "code" => $this->user_code,
            "name" => ucwords($this->name),
            "email" => $this->email,
            "photo" => $this->photo,
            "role" => $this->role->name,
            "created_at" => $this->created_at,
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
