<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilitySimpleResource extends JsonResource
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
            "name" => $this->name,
            "name_upper" => ucwords($this->name),
            "description" => $this->description,
            "capacity" => $this->capacity,
            "price_per_hour" => "Rp. ". number_format($this->price_per_hour, 0, ',', '.'),
            "status" => $this->status,
            "cover_image" => asset($this->cover_image),
            "image_previews" => FacilityPreviewSimpleResource::collection($this->facilityPreview),
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i"),
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
