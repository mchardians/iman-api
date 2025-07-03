<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FacilityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "facilities" => FacilitySimpleResource::collection($this->collection),
            "meta" => [
                "total" => $this->total(),
                "per_page" => $this->perPage(),
                "current_page" => $this->currentPage(),
                "last_page" => $this->lastPage(),
                "first_page_url" => $this->url(1),
                "last_page_url" => $this->url($this->lastPage()),
                "next_page_url" => $this->nextPageUrl(),
                "prev_page_url" => $this->previousPageUrl(),
                "path" => $this->path(),
                "from" => $this->firstItem(),
                "to" => $this->lastItem(),
            ]
        ];
    }
}
