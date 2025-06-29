<?php

namespace App\Services;

use App\Repositories\Contracts\UserContract;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function __construct(protected UserContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(array $filters = []) {
        return $this->userRepository->all($filters);
    }

    public function getAllPaginatedUsers(?string $perPage = null, array $filters = []) {
        return $this->userRepository->paginate($perPage, $filters);
    }

    public function getUserById(string $id) {
        try {
            return $this->userRepository->findOrFail($id);
        } catch (Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }
    }

    public function createUser(array $data) {
        try {
            if(isset($data["photo"]) && $data["photo"] instanceof \Illuminate\Http\UploadedFile) {
                $data["photo"] = "storage/". $this->uploadPhoto($data["photo"]);
            }

            return $this->userRepository->create($data);
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function updateUser(string $id, array $data) {
        try {
            $user = $this->getUserById($id);

            if(isset($data["photo"]) && $data["photo"] instanceof \Illuminate\Http\UploadedFile) {
                $profilePath = str_replace("storage/", "", $user->photo);

                if(!empty($user->photo) && Storage::disk('public')->exists(path: $profilePath)) {
                    Storage::disk('public')->delete($profilePath);
                }

                $data["photo"] = "storage/". $this->uploadPhoto($data["photo"]);
            }

            return $this->userRepository->update($id, $data) === true ? $user->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteUser(string $id) {
        try {
            $user = $this->getUserById($id);

            if($user->id === auth()->user()->id) {
                throw new Exception("You cannot delete your own account while you are logged in!");
            }

            if($user->news()->exists()) {
                throw new Exception(
                    "This user cannot be deleted because they are the author of existing news articles.\n You must first delete their articles or reassign them to another author!"
                );
            }

            $isDeleted = $this->userRepository->delete($id);

            if($isDeleted) {
                $profilePath = str_replace("storage/", "", $user->photo);

                if(!empty($user->photo) && Storage::disk("public")->exists($profilePath)) {
                    Storage::disk("public")->delete($profilePath);
                }
            }

            return $isDeleted === true ? $user : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function uploadPhoto($photo) {
        $photoName = $photo->hashName();

        return $photo->storePubliclyAs("photo-profiles", $photoName, "public");
    }
}