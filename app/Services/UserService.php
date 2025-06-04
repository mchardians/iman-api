<?php

namespace App\Services;

use App\Libraries\CodeGeneration;
use App\Models\User;
use App\Repositories\Contracts\UserContract;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function __construct(protected UserContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers() {
        return $this->userRepository->all();
    }

    public function getUserById(string $id) {
        try {
            return $this->userRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }
    }

    public function createUser(array $data) {
        if(isset($data["photo"]) && $data["photo"] instanceof \Illuminate\Http\UploadedFile) {
            $data["photo"] = $this->uploadPhoto($data["photo"]);
        }

        return $this->userRepository->create($data);
    }

    public function updateUser(string $id, array $data) {
        try {
            $user = $this->getUserById($id);

            if(isset($data["photo"]) && $data["photo"] instanceof \Illuminate\Http\UploadedFile) {
                $profilePath = str_replace("storage/", "", $user->photo);

                if(!empty($financeIncome->transaction_receipt) && Storage::disk('public')->exists(path: $profilePath)) {
                    Storage::disk('public')->delete($profilePath);
                }

                $data["photo"] = $this->uploadPhoto($data["photo"]);
            }
            return $this->userRepository->update($id, $data) === true ? $user->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteUser(string $id) {
        try {
            $user = $this->getUserById($id);
            $profilePath = str_replace("storage/", "", $user->photo);

            if(!empty($user->photo) && Storage::disk("public")->exists($profilePath)) {
                Storage::disk("public")->delete($profilePath);
            }
            
            return $this->userRepository->delete($id) === true ? $user : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function uploadPhoto($photo) {
        $photoName = $photo->hashName();

        return $photo->storePubliclyAs("user-profiles", $photoName, "public");
    }
}