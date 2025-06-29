<?php

namespace App\Services;

use App\Repositories\Contracts\FinanceCategoryContract;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceCategoryService
{
    public function __construct(protected FinanceCategoryContract $financeCategoryRepository)
    {
        $this->financeCategoryRepository = $financeCategoryRepository;
    }

    public function getAllFinanceCategories(?array $filters = []) {
        return $this->financeCategoryRepository->all($filters);
    }

    public function getAllPaginatedFinanceCategories(?string $pageSize = null, array $filters = []) {
        return $this->financeCategoryRepository->paginate($pageSize, $filters);
    }

    public function getFinanceCategoryById(string $id) {
        try {
            $user = $this->financeCategoryRepository->findOrFail($id);
        } catch (Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $user;
    }

    public function createFinanceCategory(array $data) {
        return $this->financeCategoryRepository->create($data);
    }

    public function updateFinanceCategory(string $id, array $data) {
        try {
            $financeCategory = $this->getFinanceCategoryById($id);

            return $this->financeCategoryRepository->update($id, $data) === true ? $financeCategory->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFinanceCategory(string $id) {
        try {
            $financeCategory = $this->getFinanceCategoryById($id);

            if($financeCategory->financeIncome()->exists() || $financeCategory->financeExpense()->exists()) {
                throw new Exception("This category cannot be deleted because it contains existing transactions.\n To delete this category, you must first delete or recategorize all associated transactions!");
            }

            return $this->financeCategoryRepository->delete($id) === true ? $financeCategory : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}