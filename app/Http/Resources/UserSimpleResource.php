<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
