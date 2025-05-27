<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfaqTypeSimpleResource extends JsonResource
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
            "infaq_type_code" => $this->infaq_type_code,
            "name" => ucwords($this->name),
            "description" => $this->description,
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i"),
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
