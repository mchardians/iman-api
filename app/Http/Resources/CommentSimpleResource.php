<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "content" => $this->content,
            "user" => new UserSimpleResource($this->user),
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y"),
            "created_at_human" => $this->created_at->diffforhumans(),
            "replies" => CommentSimpleResource::collection($this->reply)
        ];
    }
}
