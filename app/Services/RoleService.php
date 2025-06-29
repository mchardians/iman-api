<?php

namespace App\Services;

use App\Repositories\Contracts\RoleContract;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleService
{
    public function __construct(protected RoleContract $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles(array $filters = []) {
        return $this->roleRepository->all($filters);
    }

    public function getAllPaginatedRoles(?string $pageSize = null, array $filters = []) {
        return $this->roleRepository->paginate($pageSize, $filters);
    }

    public function getRoleById(string $id) {
        try {
            $role = $this->roleRepository->findOrFail($id);
        } catch (Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $role;
    }

    public function createRole(array $data) {
        return $this->roleRepository->create($data);
    }

    public function updateRole(string $id, array $data) {
        try {
            $role = $this->getRoleById($id);

            return $this->roleRepository->update($id, $data) === true ? $role->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteRole(string $id) {
        try {
            $role = $this->getRoleById($id);

            if($role->user()->exists()) {
                throw new Exception("This role cannot be deleted while it is assigned to users.\n Please unassign this role from all users before trying again!");
            }

            return $this->roleRepository->delete($id) === true ? $role : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}