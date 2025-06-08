<?php

namespace App\Repositories\Eloquent;
use App\Models\NewsCategory;
use App\Repositories\Contracts\NewsCategoryContract;

class NewsCategoryRepository Implements NewsCategoryContract
{
    protected $newsCategory;

    public function __construct(NewsCategory $newsCategory)
    {
        $this->newsCategory = $newsCategory;
    }

    // Add repository methods here

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->newsCategory->select("id", "news_category_code", "name", "slug", "created_at")
        ->latest()
        ->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->newsCategory->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->newsCategory->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->newsCategory->select("id", "news_category_code", "name", "created_at")
        ->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->newsCategory->findOrFail($id)->updateOrFail($data);
    }
}