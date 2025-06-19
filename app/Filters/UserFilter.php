<?php

namespace App\Filters;
use App\Filters\ApiFilter;

class UserFilter extends ApiFilter {
    protected $safeParams = [
        "name" => ["eq", "lk"],
        "email" => ["eq", "lk"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}