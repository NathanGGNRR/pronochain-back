<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'flag_url' => $this->flag_url,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'leagues' => LeagueResource::collection(
                in_array('country.leagues', explode(',', $request->includes), true)
                    ? $this->leagues
                    : $this->whenLoaded('leagues')
            ),
        ];
    }
}
