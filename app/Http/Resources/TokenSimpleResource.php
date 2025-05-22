<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "auth" => [
                "access_token" => $this["token"],
                "token_type" => $this["type"],
                "expires_in" => $this["ttl"],
                "expires_in_human" => CarbonInterval::seconds($this["ttl"])->cascade()->forHumans([
                    "parts" => 3,
                    "short" => true,
                    "join" => true,
                ])
            ],
            "user" => new UserSimpleResource(auth()->setToken($this["token"])->user())
        ];
    }
}
