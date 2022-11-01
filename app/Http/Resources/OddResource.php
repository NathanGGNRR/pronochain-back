<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OddResource extends JsonResource
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
            'value' => $this->value,
            'odd_type_id' => $this->odd_type_id,
            'game_id' => $this->game_id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'odd_type' => new OddTypeResource(
                in_array('odd.odd_type', explode(',', $request->includes), true)
                    ? $this->oddType
                    : $this->whenLoaded('oddType')
            ),
        ];
    }
}
