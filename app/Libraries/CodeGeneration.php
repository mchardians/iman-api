<?php

namespace App\Libraries;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CodeGeneration {
    private string $model;
    private string $column;
    private string $prefix;
    private string $code;

    public function __construct(string $model, string $column, string $prefix) {
        $this->model = $model;
        $this->column = $column;
        $this->prefix = $prefix;

        $this->generate();
    }

    public function getGeneratedCode() {
        return $this->code;
    }

    private function setGeneratedCode(string $code) {
        $this->code = $code;
    }

    private function generate() {
        try {
            $lastCode = $this->getLastCodeRecord();

            $currentYear = date("y");
            $currentMonth = date("m");

            if($lastCode) {
                $month = explode("/", $lastCode->{$this->column});
                $month = substr($month[1], 2, 4);

                if($month === $currentMonth) {
                    $lastRecord = explode("/", $lastCode->{$this->column});
                    $lastRecord[2] = (int)++$lastRecord[2];

                    $postfix = str_pad($lastRecord[2], 4, '0', STR_PAD_LEFT);
                    $newCode = "$this->prefix/$currentYear$currentMonth/$postfix";

                    $this->setGeneratedCode($newCode);
                }else {
                    $newCode = "$this->prefix/$currentYear$currentMonth/0001";

                    $this->setGeneratedCode($newCode);
                }
            } else {
                $newCode = "$this->prefix/$currentYear$currentMonth/0001";

                $this->setGeneratedCode($newCode);
            }

            $this->setGeneratedCode($newCode);
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    private function getLastCodeRecord() {
        return $this->model::select($this->column)
        ->whereMonth("created_at", Carbon::now()->month)
        ->whereYear("created_at", Carbon::now()->year)
        ->where(DB::raw("substr({$this->column}, 1, 3)"), "=", $this->prefix)
        ->orderByDesc($this->column)
        ->first();
    }
}