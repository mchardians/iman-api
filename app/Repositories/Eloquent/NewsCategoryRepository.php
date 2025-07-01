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
    public function baseQuery() {
        return $this->newsCategory->select("id", "news_category_code", "name", "slug", "created_at");
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
        return $this->baseQuery()->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->newsCategory->findOrFail($id)->updateOrFail($data);
    }

}