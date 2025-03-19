<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory;
    protected $fillable = ['facility_code', 'name', 'description', 'capacity', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($facility) {
            $facility->facility_code = (string) Str::uuid();
        });
    }
}

