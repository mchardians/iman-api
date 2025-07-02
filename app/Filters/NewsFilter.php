<?php

namespace App\Filters;

class NewsFilter extends ApiFilter {
    protected $safeParams = [
        "title" => ["eq", "lk"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}