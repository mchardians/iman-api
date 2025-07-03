<?php

namespace App\Services;

use Exception;
use App\Repositories\Contracts\CommentContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentService
{
    public function __construct(protected CommentContract $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAllNewsComments(string $id) {
        return $this->commentRepository->all($id);
    }

    public function createOrReplyComment(array $data) {
        return $this->commentRepository->create($data);
    }

    public function getCommentById(string $id) {
        return $this->commentRepository->findOrFail($id);
    }

    public function updateNewsComment(string $id, array $data) {
        try {
            $comment = $this->getCommentById($id);
            $isUpdated = $this->commentRepository->update($id, $data);

            return $isUpdated === true ? $comment->fresh() : throw new Exception("An error occured while updating the comment!");
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteNewsComment(string $id) {
        try {
            $comment = $this->getCommentById($id);
            $isDeleted = $this->commentRepository->delete($id);

            return $isDeleted === true ? $comment : throw new Exception("An error occured while deleting the comment!");
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        };
    }
}