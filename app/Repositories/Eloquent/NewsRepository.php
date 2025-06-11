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
    public function all() {
        return $this->news->select(
            "id", "news_code", "title", "slug",
            "thumbnail", "content", "excerpt", "status",
            "user_id", "published_at", "archived_at",
            "created_at",
        )->with(["user", "newsCategory"])->latest()->get();
    }

    public function whereEquals(string $column, string $value) {
        return $this->news->select(
            "id", "news_code", "title", "slug",
            "thumbnail", "content", "excerpt", "status",
            "user_id", "published_at", "archived_at",
            "created_at",
        )->where($column, "=", $value)
        ->with(["user", "newsCategory"])
        ->orderBy($column)
        ->get();
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
        return $this->news->select(
            "id", "news_code", "title", "slug",
            "thumbnail", "content", "excerpt", "status",
            "user_id", "published_at", "archived_at",
            "created_at",
        )->with(["user", "newsCategory"])->findOrFail($id);
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
    public function expose() {
        return $this->news->select(
            "id", "news_code", "title", "slug",
            "thumbnail", "content", "excerpt", "user_id",
            "published_at"
        )->with(["user", "newsCategory"])
        ->where("status", "=", "published")
        ->whereNull("archived_at")
        ->latest()->get();
    }
}