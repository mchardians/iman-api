<?php

namespace App\Repositories\Contracts;

interface CommentContract
{
    public function baseQuery(string $id);
    public function all(string $id);
    public function findOrFail(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}