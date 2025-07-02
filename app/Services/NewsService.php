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

    public function getAllNews(array $filters = []) {
        return $this->newsRepository->all($filters);
    }

    public function getAllPublishedNews(array $filters = []) {
        return $this->newsRepository->whereAllPublished($filters);
    }

    public function getAllPaginatedNews(?string $pageSize = null, array $filters = []) {
        return $this->newsRepository->paginate($pageSize, $filters);
    }

    public function getNewsBySlug(string $slug) {
        return $this->newsRepository->firstOrFail($slug);
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
            $isDeleted = $this->newsRepository->delete($id);

            if($isDeleted) {
                $thumbnailPath = str_replace("storage/", "", $user->thumbnail);

                if(!empty($user->thumbnail) && Storage::disk("public")->exists($thumbnailPath)) {
                    Storage::disk("public")->delete($thumbnailPath);
                }
            }

            return $isDeleted === true ? $user : false;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function setNewsStatus(string $id, string $status) {
        try {
            $news = $this->getNewsById($id);

            $allowedStatusTransition = [
                "drafted" => ["published"],
                "published" => ["archived"],
                "archived" => ["drafted"]
            ];

            if(!in_array($status, $allowedStatusTransition[$news->status])) {
                throw new Exception("Not allowed status transition!");
            }

            match ($status) {
                "drafted" => (function() use ($news) {
                    $news->status = "drafted";
                    $news->published_at = null;
                    $news->archived_at = null;
                })(),
                "published" => (function() use($news) {
                    $news->status = "published";
                    $news->published_at = now();
                    $news->archived_at = null;
                })(),
                "archived" =>  (function() use($news) {
                    $news->status = "archived";
                    $news->archived_at = now();
                }),
            };

            $isStatusUpdated = $this->newsRepository->update($id, $news);

            return $isStatusUpdated === true ? $news->fresh() : throw new Exception("Fail to update the news item status");;
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    private function uploadThumbnail(\Illuminate\Http\UploadedFile $thumbnail) {
        $fileName = $thumbnail->hashName();

        return $thumbnail->storePubliclyAs("thumbnails", $fileName, "public");
    }
}