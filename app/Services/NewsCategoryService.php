<?php

namespace App\Services;

use App\Repositories\Contracts\NewsCategoryContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NewsCategoryService
{
    public function __construct(protected NewsCategoryContract $newsCategoryRepository)
    {
        $this->newsCategoryRepository = $newsCategoryRepository;
    }

    public function getAllNewsCategories() {
        return $this->newsCategoryRepository->all();
    }

    public function getNewsCategoryById(string $id) {
        try {
            $newsCategory = $this->newsCategoryRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $newsCategory;
    }

    public function createNewsCategory(array $data) {
        return $this->newsCategoryRepository->create($data);
    }

    public function updateNewsCategory(string $id, array $data) {
        $newsCategory = $this->getNewsCategoryById($id);

        try {
            return $this->newsCategoryRepository->update($id, $data) === true ? $newsCategory->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteNewsCategory(string $id) {
        $newsCategory = $this->getNewsCategoryById($id);

        try {
            return $this->newsCategoryRepository->delete($id) === true ? $newsCategory : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}