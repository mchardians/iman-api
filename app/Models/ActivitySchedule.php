<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySchedule extends Model
{
    use HasFactory, AutoResourceCodeGeneration;

    protected $fillable = [
        "activity_code", "title", "description", "day_of_week","start_time",
        "end_time", "location", "repeat_type", "status", "facility_id"
    ];

    protected $casts = [
        "start_time" => "datetime:H:i",
        "end_time" => "datetime:H:i"
    ];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "activity_code",
            "prefix" => "ATVT"
        ];
    }
}
