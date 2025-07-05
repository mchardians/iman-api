<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\FacilityContract;
use App\Repositories\Contracts\EventScheduleContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventScheduleService
{
    public function __construct(
        protected EventScheduleContract $eventScheduleRepository,
        protected FacilityContract $facilityRepository
    )
    {
        $this->eventScheduleRepository = $eventScheduleRepository;
        $this->facilityRepository = $facilityRepository;
    }

    public function getAllEventSchedules(array $filters = []) {
        return $this->eventScheduleRepository->all($filters);
    }

    public function getAllPaginatedEventSchedules(?string $pageSize = null, array $filters = []) {
        return $this->eventScheduleRepository->paginate($pageSize, $filters);
    }

    public function createEventSchedule(array $data) {
        if(isset($data["banner"]) && $data["banner"] instanceof \Illuminate\Http\UploadedFile) {
            $data["banner"] = "storage/". $this->uploadBanner($data["banner"]);
        }

        $facility = $this->facilityRepository->first([
            "name" => $data["location"]
        ]);

        if(!empty($facility)) {
            $data["facility_id"] = $facility?->id;
        }

        return $this->eventScheduleRepository->create($data);
    }

    public function getEventScheduleById(string $id) {
        try {
            return $this->eventScheduleRepository->findOrFail($id);
        } catch (Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }
    }

    public function updateEventSchedule(string $id, array $data) {
        try {
            $eventSchedule = $this->getEventScheduleById($id);

            if(isset($data["banner"]) && $data["banner"] instanceof \Illuminate\Http\UploadedFile) {
                $bannerPath = str_replace("storage/", "", $eventSchedule->banner);

                if(!empty($news->banner) && Storage::disk('public')->exists(path: $bannerPath)) {
                    Storage::disk('public')->delete($bannerPath);
                }

                $data["banner"] = "storage/". $this->uploadBanner($data["banner"]);
            }

            $facility = $this->facilityRepository->first([
                "name" => $data["location"]
            ]);
            if(!empty($facility)) {
                $data["facility_id"] = $facility?->id;
            }

            return $this->eventScheduleRepository->update($id, $data) === true ? $eventSchedule->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteEventSchedule(string $id) {
        try {
            $eventSchedule = $this->getEventScheduleById($id);
            $isDeleted = $this->eventScheduleRepository->delete($id);

            if($isDeleted) {
                $bannerPath = str_replace("storage/", "", $eventSchedule->banner);

                if(!empty($eventSchedule->banner) && Storage::disk("public")->exists($bannerPath)) {
                    Storage::disk("public")->delete($bannerPath);
                }
            }

            return $isDeleted === true ? $eventSchedule : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    private function uploadBanner(\Illuminate\Http\UploadedFile $banner) {
        $fileName = $banner->hashName();

        return $banner->storePubliclyAs("event-banners", $fileName, "public");
    }
}