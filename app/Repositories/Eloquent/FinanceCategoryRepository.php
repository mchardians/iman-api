<?php

namespace App\Repositories\Eloquent;

use App\Models\FinanceCategory;
use App\Repositories\Contracts\FinanceCategoryContract;



class FinanceCategoryRepository implements FinanceCategoryContract
{
    protected $financeCategory;
    public function __construct(FinanceCategory $financeCategory) {
        $this->financeCategory = $financeCategory;
    }

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->financeCategory->select('id', 'finance_category_code', 'name', 'type', 'created_at')->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->financeCategory->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->financeCategory->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->financeCategory->select('id', 'finance_category_code', 'name', 'type', 'created_at')->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->financeCategory->findOrFail($id)->updateOrFail($data);
    }
}