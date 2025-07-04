<?php

namespace App\Repositories\Eloquent;
use App\Models\ActivitySchedule;
use App\Repositories\Contracts\ActivityScheduleContract;

class ActivityScheduleRepository Implements ActivityScheduleContract
{
    protected $activitySchedule;

    public function __construct(ActivitySchedule $activitySchedule)
    {
        $this->activitySchedule = $activitySchedule;
    }

    // Add repository methods here
    /**
     * @inheritDoc
     */
    public function baseQuery() {
        return $this->activitySchedule->select(
            "id", "activity_code", "title", "description", "day_of_week", "start_time", "end_time",
            "location", "repeat_type", "status", "facility_id", "created_at"
        )->with("facility");
    }

    /**
     * @inheritDoc
     */
    public function all(array $filters = []) {
        return $this->baseQuery()->where($filters)->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return $this->activitySchedule->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->activitySchedule->findOrFail($id)->deleteOrFail();
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
    public function paginate(string|null $perPage = null, array $filters = []) {
        return $this->baseQuery()->where($filters)
        ->latest()
        ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->activitySchedule->findOrFail($id)->updateOrFail($data);
    }
}