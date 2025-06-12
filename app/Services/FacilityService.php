<?php

namespace App\Services;

use App\Models\FacilityPreview;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\FacilityContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FacilityService
{
    public function __construct(protected FacilityContract $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    public function getAllFacilities() {
        return $this->facilityRepository->all();
    }

    public function getFacilityById(string $id) {
        return $this->facilityRepository->findOrFail($id);
    }

    public function createFacility(array $data) {
        if(isset($data["cover_image"]) && $data["cover_image"] instanceof \Illuminate\Http\UploadedFile) {
            $data["cover_image"] = "storage/". $this->uploadCoverImage($data["cover_image"]);
        }

        $facility = $this->facilityRepository->create($data);

        if(isset($data["facility_previews"]) && !empty($data["facility_previews"])) {
            foreach ($data["facility_previews"] as $facilityPreview) {
                $path = $facilityPreview->storePubliclyAs("facility-previews", $facilityPreview->hashName(), "public");
                $facility->facilityPreview()->create(["image_path" => Storage::url($path)]);
            }
        }

        return $facility;
    }

    public function updateFacility(string $id, array $data) {
        try {
            $facility = $this->getFacilityById($id);

            if(isset($data["cover_image"]) && $data["cover_image"] instanceof \Illuminate\Http\UploadedFile) {
                $coverImagePath = str_replace("storage/", "", $facility->cover_image);

                if(!empty($facility->cover_image) && Storage::disk('public')->exists(path: $coverImagePath)) {
                    Storage::disk('public')->delete($coverImagePath);
                }

                $data["cover_image"] = "storage/". $this->uploadCoverImage($data["cover_image"]);
            }

            if(isset($data["facility_previews"]) && !empty($data["facility_previews"])) {
                foreach ($data["facility_previews"] as $facilityPreview) {
                    $path = $facilityPreview->storePubliclyAs("facility-previews", $facilityPreview->hashName(), "public");
                    $facility->facilityPreview()->create(["image_path" => Storage::url($path)]);
                }
            }

            if(isset($data["remove_facility_preview_id"]) && !empty($data["remove_facility_preview_id"])) {
                $facilityPreviews = FacilityPreview::whereIn("id", $data["remove_facility_preview_id"])->get();

                foreach($facilityPreviews as $facilityPreview) {
                    Storage::disk("public")->delete("facility-previews" . $facilityPreview->image_path);
                    $facilityPreview->delete();
                }
            }

            return $this->facilityRepository->update($id, $data) === true ? $facility->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFacility(string $id) {
        try {
            $facility = $this->getFacilityById($id);
            $coverImagePath = str_replace("storage/", "", $facility->cover_image);

            if(!empty($facility->cover_image) && Storage::disk("public")->exists($coverImagePath)) {
                Storage::disk("public")->delete($coverImagePath);
            }

            foreach ($facility->facilityPreview as $facilityPreview) {
                if (Storage::disk('public')->exists('facilities/' . $facilityPreview->image_path)) {
                    Storage::disk('public')->delete('facilities/' . $facilityPreview->image_path);
                }
            }

            return $this->facilityRepository->delete($id) === true ? $facility : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    private function uploadCoverImage($coverImage) {
        $fileName = $coverImage->hashName();

        return $coverImage->storePubliclyAs("facility-previews", $fileName, "public");
    }
}