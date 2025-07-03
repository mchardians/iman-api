<?php

namespace App\Filters;

class FacilityFilter extends ApiFilter {
    protected $safeParams = [
        "name" => ["eq", "lk"],
        "capacity" => ["eq"],
        "price_per_hour" => ["eq", "lk"],
        "status" => ["eq"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}