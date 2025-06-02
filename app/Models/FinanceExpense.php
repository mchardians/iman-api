<?php

namespace App\Models;

use App\Traits\AutoTransactionalCodeGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceExpense extends Model
{
    use HasFactory, AutoTransactionalCodeGeneration;

    protected $fillable = [
        "expense_transaction", "date", "finance_category_id",
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
            "resource_prefix" => "FIEXP",
            "column" => "expense_transaction"
        ];
    }
}
