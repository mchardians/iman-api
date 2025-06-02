<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    use HasFactory, AutoResourceCodeGeneration;

    protected $fillable = [
        "finance_category_code", "name", "type"
    ];

    public function financeIncome() {
        return $this->hasMany(FinanceIncome::class);
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "finance_category_code",
            "prefix" => "FICAT"
        ];
    }
}
