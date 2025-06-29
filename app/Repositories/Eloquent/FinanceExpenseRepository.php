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

    /**
     * @inheritDoc
     */
    public function baseQuery() {
        return $this->financeExpense->select(
            "id", "expense_transaction", "date",
            "finance_category_id", "description", "amount", "transaction_receipt", "created_at"
        )->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "expense");
        });
    }

    /**
     * @inheritDoc
     */
    public function all(array $filters = []) {
        return $this->baseQuery()->where($filters)->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function paginate(string|null $perPage = null, array $filters = []) {
        return $this->baseQuery()->where($filters)->latest()->paginate($perPage);
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
        return $this->baseQuery()->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->financeExpense->findOrFail($id)->updateOrFail($data);
    }

}