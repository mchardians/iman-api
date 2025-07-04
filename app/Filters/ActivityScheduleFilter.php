<?php

namespace App\Filters;

class ActivityScheduleFilter extends ApiFilter {
    protected $safeParams = [
        "title" => ["eq", "lk"],
        "description" => ["lk"],
        "location" => ["eq"],
        "status" => ["eq"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}