<?php

namespace App\Filters;
use App\Filters\ApiFilter;

class RoleFilter extends ApiFilter {
    protected $safeParams = [
        "name" => ["eq", "lk"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}