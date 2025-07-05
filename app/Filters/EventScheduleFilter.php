<?php

namespace App\Filters;

class EventScheduleFilter extends ApiFilter {
    protected $safeParams = [
        "title" => ["eq", "lk"],
        "description" => ["lk"],
        "location" => ["eq", "lk"],
        "speaker" => ["eq", "lk"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}