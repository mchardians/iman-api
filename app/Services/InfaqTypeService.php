<?php

namespace App\Services;

use App\Models\InfaqType;
use App\Libraries\CodeGeneration;
use App\Repositories\Contracts\InfaqTypeContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InfaqTypeService
{
    public function __construct(protected InfaqTypeContract $infaqTypeRepository)
    {
        $this->infaqTypeRepository = $infaqTypeRepository;
    }

    public function getAllInfaqTypes() {
        return $this->infaqTypeRepository->all();
    }

    public function getInfaqTypeById(string $id) {
        try {
            $user = $this->infaqTypeRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $user;
    }

    public function createInfaqType(array $data) {
        return $this->infaqTypeRepository->create($data);
    }

    public function updateInfaqType(string $id, array $data) {
        $infaqType = $this->getInfaqTypeById($id);

        try {
            return $this->infaqTypeRepository->update($id, $data) === true ? $infaqType->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteInfaqType(string $id) {
        $infaqType = $this->getInfaqTypeById($id);

        try {
            return $this->infaqTypeRepository->delete($id) === true ? $infaqType : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}