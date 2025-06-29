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
    public function baseQuery() {
        return $this->financeIncome->select(
            "id", "income_transaction", "date", "finance_category_id",
            "description", "amount", "transaction_receipt", "created_at"
        )->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "income");
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
        return $this->baseQuery()->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->financeIncome->findOrFail($id)->updateOrFail($data);
    }

}