<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'logo' => $this->logo,
            'country_id' => $this->country_id,
            'sport_id' => $this->sport_id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'country' => new CountryResource(
                in_array('team.country', explode(',', $request->includes), true)
                    ? $this->country
                    : $this->whenLoaded('country')
            ),
            'sport' => new SportResource(
                in_array('team.sport', explode(',', $request->includes), true)
                    ? $this->sport
                    : $this->whenLoaded('sport')
            ),
        ];
    }
}
