<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Carbon::setLocale("id");

        return [
            "id" => $this->id,
            "title" => $this->title,
            "upper_title" => ucwords($this->title),
            "slug" => $this->slug,
            "content" => $this->content,
            "excerpt" => $this->excerpt,
            "status" => $this->status,
            "thumbnail" => $this->thumbnail ? asset($this->thumbnail) : null,
            "news_category" => NewsCategorySimpleResource::collection($this->newsCategory),
            "published_at" => $this->published_at ? Carbon::parse($this->published_at)->translatedFormat("d F Y H:i") : null,
            "published_at_human" => $this->published_at?->diffforhumans(),
            "archived_at" => $this->archived_at ? Carbon::parse($this->archived_at)->translatedFormat("d F Y H:i") : null,
            "archived_at_human" => $this->archived_at?->diffforhumans(),
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i"),
            "created_at_human" => $this->created_at->diffforhumans(),
            "author" => new UserSimpleResource($this->user)
        ];
    }
}
