<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityPreview extends Model
{
    use HasFactory;

    protected $fillable = ['facility_id', 'image_path'];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }
}
