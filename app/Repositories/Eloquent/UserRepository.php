<?php

namespace App\Repositories\Eloquent;
use App\Models\User;
use App\Repositories\Contracts\UserContract;

class UserRepository Implements UserContract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Add repository methods here

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->user->select('name', 'email', 'photo', 'created_at')->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->user->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->user->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->user->select('name', 'email', 'photo', 'created_at')->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->user->findOrFail($id)->update($data);
    }
}