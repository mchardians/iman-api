<?php

namespace App\Repositories\Eloquent;
use App\Models\EventSchedule;
use App\Repositories\Contracts\EventScheduleContract;

class EventScheduleRepository implements EventScheduleContract
{
    protected $eventSchedule;

    public function __construct(EventSchedule $eventSchedule)
    {
        $this->eventSchedule = $eventSchedule;
    }

    // Add repository methods here
    /**
     * @inheritDoc
     */
    public function baseQuery() {
        return $this->eventSchedule->select(
            "id", "event_schedule_code", "title", "description", "event_date", "start_time", "end_time", "location",
            "speaker", "banner", "status", "facility_id", "created_at"
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
        return $this->eventSchedule->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id) {
        return $this->eventSchedule->findOrFail($id)->deleteOrFail();
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
        return $this->baseQuery()->where($filters)->latest()->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function update(string $id, array $data) {
        return $this->eventSchedule->findOrFail($id)->updateOrFail($data);
    }
}