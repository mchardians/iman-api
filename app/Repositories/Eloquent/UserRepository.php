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

    public function baseQuery() {
        return $this->user->with('role')
        ->select('id', 'user_code', 'name', 'email', 'photo', 'role_id', 'created_at');
    }

    /**
     * @inheritDoc
     */
    public function all(array $filters = []) {
        return $this->baseQuery()->where($filters)
        ->latest()
        ->get();
    }

    /**
     * @inheritDoc
     */
    public function paginate(string|null $perPage = null, array $filters = []) {
        return $this->baseQuery()->where($filters)
        ->latest()
        ->paginate($perPage);
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
        return $this->baseQuery()->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->user->findOrFail($id)->updateOrFail($data);
    }
}