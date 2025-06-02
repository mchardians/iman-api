<?php

namespace App\Models;

use App\Traits\AutoTransactionalCodeGeneration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceIncome extends Model
{
    use HasFactory, AutoTransactionalCodeGeneration;

    protected $fillable = [
        "income_transaction", "date", "finance_category_id",
        "description", "amount", "transaction_receipt"
    ];

    public function financeCategory() {
        return $this->belongsTo(FinanceCategory::class);
    }

    /**
     * @inheritDoc
     */
    public function getTransactionalCodeConfig(): array {
        return [
            "transaction_prefix" => "TRX",
            "resource_prefix" => "FIICM",
            "column" => "income_transaction"
        ];
    }
}
