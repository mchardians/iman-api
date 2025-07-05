<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventScheduleSimpleResource extends JsonResource
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
            "event_schedule_code" => $this->event_schedule_code,
            "title" => $this->title,
            "title_upper" => ucwords($this->title),
            "description" => $this->description,
            "event_date" => Carbon::parse($this->event_date)->format("d-m-Y"),
            "event_date_idn_format" => Carbon::parse($this->event_date)->translatedFormat("d F Y"),
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "location" => $this->location,
            "location_upper" => ucwords($this->location),
            "speaker" => $this->speaker,
            "speaker_upper" => ucwords($this->speaker),
            "banner" => $this->banner ? asset($this->banner) : null,
            "status" => $this->status,
            "status_upper" => ucfirst($this->status),
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y"),
            "created_at_human" => $this->created_at->diffforhumans(),
            "facility" => new FacilitySimpleResource($this->facility)
        ];
    }
}
