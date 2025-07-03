<?php

namespace App\Repositories\Eloquent;

use App\Models\News;
use App\Models\Comment;
use App\Repositories\Contracts\CommentContract;

class CommentRepository implements CommentContract
{
    protected $news;
    protected $comment;
    public function __construct(News $news, Comment $comment) {
        $this->news = $news;
        $this->comment = $comment;
    }

    /**
     * @inheritDoc
     */
    public function baseQuery(string $id) {
        return $this->news->findOrFail($id)->comment()->with("user");
    }

    /**
     * @inheritDoc
     */
    public function all(string $id) {
        return $this->baseQuery($id)->latest()->get();
    }

    public function findOrFail(string $id) {
        return $this->comment->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->comment->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->comment->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->comment->findOrFail($id)->updateOrFail($data);
    }
}