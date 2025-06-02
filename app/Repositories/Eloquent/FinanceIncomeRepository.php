<?php

namespace App\Repositories\Eloquent;
use App\Models\FinanceIncome;
use App\Repositories\Contracts\FinanceIncomeContract;

class FinanceIncomeRepository Implements FinanceIncomeContract
{
    protected $financeIncome;

    public function __construct(FinanceIncome $financeIncome)
    {
        $this->financeIncome = $financeIncome;
    }

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->financeIncome->select(
            "id", "income_transaction", "date", "finance_category_id",
            "description", "amount", "transaction_receipt", "created_at"
        )->with("financeCategory")
        ->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->financeIncome->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->financeIncome->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->financeIncome->select(
            "id", "income_transaction", "date", "finance_category_id",
            "description", "amount", "transaction_receipt", "created_at"
        )->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->financeIncome->findOrFail($id)->updateOrFail($data);
    }
}