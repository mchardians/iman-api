<?php

namespace App\Repositories\Eloquent;
use App\Models\News;
use App\Repositories\Contracts\NewsContract;

class NewsRepository Implements NewsContract
{
    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    // Add repository methods here
    /**
     * @inheritDoc
     */
    public function baseQuery() {
        return $this->news->select(
            "id", "news_code", "title", "slug",
            "thumbnail", "content", "excerpt", "status",
            "user_id", "published_at", "archived_at",
            "created_at",
        )->with(["user", "newsCategory", "comment"]);
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
        return $this->news->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->news->findOrFail($id)->deleteOrFail();
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
    public function firstOrFail(string $slug) {
        return $this->news->where("slug", "=", $slug)->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->news->findOrFail($id)->updateOrFail($data);
    }

    /**
     * @inheritDoc
     */
    public function whereAllPublished(array $filters = []) {
        return $this->baseQuery()
        ->where("status", "=", "published")
        ->where($filters)
        ->latest()->get();
    }

}