<?php

namespace App\Repositories\Eloquent;
use App\Models\FinanceExpense;
use App\Repositories\Contracts\FinanceExpenseContract;

class FinanceExpenseRepository Implements FinanceExpenseContract
{
    protected $financeExpense;

    public function __construct(FinanceExpense $financeExpense)
    {
        $this->financeExpense = $financeExpense;
    }

    // Add repository methods here

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->financeExpense->select(
            "id", "expense_transaction", "date",
            "finance_category_id", "description", "amount", "created_at"
        )->with('financeCategory')
        ->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->financeExpense->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->financeExpense->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->financeExpense->select(
            "id", "expense_transaction", "date",
            "finance_category_id", "description", "amount", "created_at"
        )->with("financeCategory")
        ->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->financeExpense->findOrFail($id)->updateOrFail($data);
    }
}