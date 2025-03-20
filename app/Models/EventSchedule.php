<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_schedule_code',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_recurring',
        'recurring_type',
        'recurring_day',
    ];
}
