<?php

namespace App\Services;

use App\Http\Resources\ActivityScheduleSimpleResource;
use Exception;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Repositories\Contracts\FacilityContract;
use App\Repositories\Contracts\ActivityScheduleContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ActivityScheduleService
{
    public function __construct(
        protected ActivityScheduleContract $activityScheduleRepository,
        protected FacilityContract $facilityRepository
    )
    {
        $this->activityScheduleRepository = $activityScheduleRepository;
    }

    public function getAllActivitySchedules(array $filters = []) {
        return $this->activityScheduleRepository->all($filters);
    }

    public function getAllPaginatedActivitySchedules(?string $pageSize = null, array $filters = []) {
        return $this->activityScheduleRepository->paginate($pageSize, $filters);
    }

    public function getActivityScheduleById(string $id) {
        return $this->activityScheduleRepository->findOrFail($id);
    }

    public function createActivitySchedule(array $data) {
        try {
            $facility = $this->facilityRepository->first([
                "name" => $data["location"]
            ]);

            if(!empty($facility)) {
                $data["facility_id"] = $facility?->id;
            }

            return $this->activityScheduleRepository->create($data);
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function updateActivitySchedule(string $id, array $data) {
        try {
            $activitySchedule = $this->getActivityScheduleById($id);

            return $this->activityScheduleRepository->update($id, $data) === true?
            $activitySchedule->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function setActivityScheduleStatus(string $id, array $data) {
        try {
            $activitySchedule = $this->getActivityScheduleById($id);
            
            $data = [
                "status" => $data["status"]
            ];

            return $this->activityScheduleRepository->update($id, $data) === true?
            $activitySchedule->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteActivitySchedule(string $id) {
        try {
            $activitySchedule = $this->activityScheduleRepository->findOrFail($id);

            return $this->activityScheduleRepository->delete($id) === true ? $activitySchedule : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}