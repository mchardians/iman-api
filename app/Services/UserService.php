<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\CodeGeneration;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Eloquent\UserRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    protected UserRepository $userRepository;
    private string $userCode;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userCode = (new CodeGeneration(User::class, "user_code", "USR"))->getGeneratedCode();
    }

    public function getAllUsers() {
        return $this->userRepository->all();
    }

    public function getUserById(string $id) {
        try {
            $user = $this->userRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $user;
    }

    private function getUserCode(): string {
        return $this->userCode;
    }

    public function createUser(array $data) {
        if(isset($data["photo"]) && $data["photo"] instanceof \Illuminate\Http\UploadedFile) {
            $data["photo"] = $this->uploadPhoto($data["photo"]);
        }

        return $this->userRepository->create([
            "user_code" => $this->getUserCode(),
            ...$data,
        ]);
    }

    public function updateUser(string $id, array $data) {
        $user = $this->getUserById($id);

        if(isset($data["photo"]) && $data["photo"] instanceof \Illuminate\Http\UploadedFile) {
            if(!empty($user->photo) && Storage::disk('public')->exists(path: $user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $data["photo"] = $this->uploadPhoto($data["photo"]);
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(string $id) {
        $user = $this->getUserById($id);

        if(!empty($user->photo) && Storage::disk("public")->exists($user->photo)) {
            Storage::disk("public")->delete($user->photo);
        }

        return $this->userRepository->delete($id);
    }

    public function uploadPhoto($photo) {
        $photoName = $photo->hashName();

        return $photo->storePubliclyAs("user-profile", $photoName, "public");
    }
}