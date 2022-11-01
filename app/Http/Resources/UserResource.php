<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'has_blocked_invitation' => (bool) $this->has_blocked_invitation,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'friends' => $this->when(in_array('user.friends', explode(',', $request->includes), true), $this->friends),
            'friend_requests' => self::collection(
                in_array('user.friend_requests', explode(',', $request->includes), true)
                    ? $this->friendRequests
                    : $this->whenLoaded('friendRequests')
            ),
            'odds' => OddResource::collection(
                in_array('user.odds', explode(',', $request->includes), true)
                    ? $this->odds
                    : $this->whenLoaded('odds')
            ),
        ];
    }
}
