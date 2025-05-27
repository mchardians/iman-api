<?php

namespace App\Repositories\Eloquent;
use App\Models\InfaqType;
use App\Repositories\Contracts\InfaqTypeContract;

class InfaqTypeRepository Implements InfaqTypeContract
{
    protected $infaqType;

    public function __construct(InfaqType $infaqType)
    {
        $this->infaqType = $infaqType;
    }

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->infaqType->select("id", "infaq_type_code", "name", "description", "created_at")->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->infaqType->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->infaqType->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->infaqType->select("id", "infaq_type_code", "name", "description", "created_at")->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->infaqType->findOrFail($id)->updateOrFail($data);
    }
}