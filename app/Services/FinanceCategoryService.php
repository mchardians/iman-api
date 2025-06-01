<?php

namespace App\Services;

use App\Repositories\Contracts\FinanceCategoryContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceCategoryService
{
    public function __construct(protected FinanceCategoryContract $financeCategoryRepository)
    {
        $this->financeCategoryRepository = $financeCategoryRepository;
    }

    public function getAllFinanceCategories() {
        return $this->financeCategoryRepository->all();
    }

    public function getFinanceCategoryById(string $id) {
        try {
            $user = $this->financeCategoryRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $user;
    }

    public function createFinanceCategory(array $data) {
        return $this->financeCategoryRepository->create($data);
    }

    public function updateFinanceCategory(string $id, array $data) {
        $financeCategory = $this->getFinanceCategoryById($id);

        try {
            return $this->financeCategoryRepository->update($id, $data) === true ? $financeCategory->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFinanceCategory(string $id) {
        $financeCategory = $this->getFinanceCategoryById($id);

        try {
            return $this->financeCategoryRepository->delete($id) === true ? $financeCategory : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}