<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityScheduleSimpleResource extends JsonResource
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
            "activity_code" => $this->activity_code,
            "title" => $this->title,
            "title_upper" => ucwords($this->title),
            "description" => $this->description,
            "day_of_week" => $this->day_of_week,
            "day_of_week_upper" => ucfirst($this->day_of_week),
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "location" => $this->location,
            "location_upper" => ucwords($this->location),
            "repeat_type" => $this->repeat_type,
            "repeat_type_upper" => ucfirst($this->repeat_type),
            "status" => $this->status,
            "facility" => new FacilitySimpleResource($this->facility),
            "created_at" => $this->created_at,
            "created_at_human" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i")
        ];
    }
}
