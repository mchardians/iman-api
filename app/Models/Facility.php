<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory, AutoResourceCodeGeneration;
    protected $fillable = [
        "facility_code", "name", "description", "capacity",
        "status", "price_per_hour", "status", "cover_image"
    ];

    public function facilityPreview() {
        return $this->hasMany(FacilityPreview::class);
    }

    public function activity() {
        return $this->hasMany(ActivitySchedule::class);
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "facility_code",
            "prefix" => "FCTY"
        ];
    }
}

