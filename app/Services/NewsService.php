<?php

namespace App\Services;

use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\NewsContract;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NewsService
{
    public function __construct(protected NewsContract $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getAllNews() {
        return $this->newsRepository->all();
    }

    public function exposeAllNews() {
        return $this->newsRepository->expose();
    }

    public function getNewsByParam(string $param) {
        return $this->newsRepository->whereEquals("status", $param);
    }

    public function getNewsById(string $id) {
        try {
            return $this->newsRepository->findOrFail($id);
        } catch (Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }
    }

    public function createNews(array $data) {
        if(isset($data["thumbnail"]) && $data["thumbnail"] instanceof \Illuminate\Http\UploadedFile) {
            $data["thumbnail"] = "storage/". $this->uploadThumbnail($data["thumbnail"]);
        }

        $data["user_id"] = Auth::user()->id;

        if (!empty($data["status"])) {
            if (in_array($data["status"], ["published", "archived"])) {
                $data = match($data["status"]) {
                    "published" => array_merge($data, [
                        "status" => "published",
                        "published_at" => now(),
                    ]),
                    "archived" => array_merge($data, [
                        "status" => "archived",
                        "archived_at" => now(),
                    ])
                };
            }
        }

        $news = $this->newsRepository->create($data);
        $news->newsCategory()->sync($data["category_id"]);

        return $news;
    }

    public function updateNews(string $id, array $data) {
        try {
            $news = $this->getNewsById($id);

            if(isset($data["thumbnail"]) && $data["thumbnail"] instanceof \Illuminate\Http\UploadedFile) {
                $thumbnailPath = str_replace("storage/", "", $news->thumbnail);

                if(!empty($news->thumbnail) && Storage::disk('public')->exists(path: $thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }

                $data["thumbnail"] = "storage/". $this->uploadThumbnail($data["thumbnail"]);
            }

            $data["user_id"] = Auth::user()->id;

            if (!empty($data["status"])) {
                if (in_array($data["status"], ["published", "archived"])) {
                    $data = match($data["status"]) {
                        "published" => array_merge($data, [
                            "status" => "published",
                            "published_at" => $model->published_at ?? now(),
                            "archived_at" => null,
                        ]),
                        "archived" => array_merge($data, [
                            "status" => "archived",
                            "archived_at" => now(),
                        ]),
                    };
                }
            }

            if(isset($data["category_id"])) {
                $news->newsCategory()->sync($data["category_id"]);
            }

            return $this->newsRepository->update($id, $data) === true ? $news->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteNews(string $id) {
        try {
            $user = $this->getNewsById($id);
            $thumbnailPath = str_replace("storage/", "", $user->thumbnail);

            if(!empty($user->thumbnail) && Storage::disk("public")->exists($thumbnailPath)) {
                Storage::disk("public")->delete($thumbnailPath);
            }

            return $this->newsRepository->delete($id) === true ? $user : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function publishNews(string $id) {
        try {
            $news = $this->newsRepository->findOrFail($id);

            $data = [
                "status" => "published",
                "published_at" => $news->published_at ?? now(),
                "archived_at" => null,
            ];

            return $this->newsRepository->update($id, $data) === true ? $news->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function archiveNews(string $id) {
        try {
            $news = $this->newsRepository->findOrFail($id);

            $data = [
                "status" => "archived",
                "archived_at" => now(),
            ];

            return $this->newsRepository->update($id, $data) === true ? $news->fresh() : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    private function uploadThumbnail(\Illuminate\Http\UploadedFile $thumbnail) {
        $fileName = $thumbnail->hashName();

        return $thumbnail->storePubliclyAs("thumbnails", $fileName, "public");
    }
}