<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    use HasFactory, AutoResourceCodeGeneration;

    protected $fillable = [
        "event_schedule_code", "title", "description", "event_date", "start_time", "end_time",
        "location", "speaker", "banner", "status", "facility_id"
    ];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "event_schedule_code",
            "prefix" => "EVTSD"
        ];
    }
}
