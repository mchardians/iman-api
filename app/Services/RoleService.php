<?php

namespace App\Services;

use App\Libraries\CodeGeneration;
use App\Models\Role;
use App\Repositories\Contracts\RoleContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleService
{
    public function __construct(protected RoleContract $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles() {
        return $this->roleRepository->all();
    }

    public function getRoleById(string $id) {
        try {
            $role = $this->roleRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $role;
    }

    public function createRole(array $data) {
        return $this->roleRepository->create($data);
    }

    public function updateRole(string $id, array $data) {
        $role = $this->getRoleById($id);

        try {
            return $this->roleRepository->update($id, $data) === true ? $role->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteRole(string $id) {
        $role = $this->getRoleById($id);

        try {
            return $this->roleRepository->delete($id) === true ? $role : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}