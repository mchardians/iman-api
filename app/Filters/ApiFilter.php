<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiFilter {
    protected $safeParams = [];
    protected $columnMap = [];
    protected $operatorMap = [];

    public function transform(Request $request) {
        $eloquentQueries = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if(!$request->has($param) || !is_array($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if(isset($query[$operator])) {
                    match (strtolower($this->operatorMap[$operator])) {
                        "like" => (function() use(&$eloquentQueries, $column, $operator, $query) {
                            $eloquentQueries[] = [$column, $this->operatorMap[$operator], "%{$query[$operator]}%"];
                        })(),
                        "between" => (function() use(&$eloquentQueries, $column, $operator, $query) {
                            if(is_string($query[$operator]) && strpos($query[$operator], ",") !== false) {
                                $betweenValues = array_map(function($value) {
                                        try {
                                            $value = trim($value);
                                            return Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
                                        } catch (\Exception $e) {
                                            throw new HttpException(400, "Invalid date format: {$value}. Expected format: d-m-Y!");
                                        }
                                }, explode(",", $query[$operator], 2));

                                if(count($betweenValues) === 2) {
                                    $eloquentQueries = [$column, $betweenValues];
                                }
                            }
                        })(),
                        default => $eloquentQueries[] = [$column, $this->operatorMap[$operator], $query[$operator]]
                    };
                }
            }
        }
        return $eloquentQueries;
    }
}