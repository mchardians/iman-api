<?php

namespace App\Repositories\Eloquent;
use App\Models\Facility;
use App\Repositories\Contracts\FacilityContract;

class FacilityRepository Implements FacilityContract
{
    protected $facility;

    public function __construct(Facility $facility)
    {
        $this->facility = $facility;
    }

    // Add repository methods here

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->facility->select(
            "id", "facility_code", "name", "description", "capacity",
            "status", "price_per_hour", "status", "cover_image", "created_at"
        )->with('facilityPreview')
        ->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->facility->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->facility->findOrFail($id)->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id) {
        return $this->facility->select(
            "id", "facility_code", "name", "description", "capacity",
            "status", "price_per_hour", "status", "cover_image", "created_at"
        )->with('facilityPreview')
        ->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->facility->findOrFail($id)->updateOrFail($data);
    }
}