<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'date' => $this->date,
            'referee' => $this->referee,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'league_id' => $this->league_id,
            'sport_id' => $this->sport_id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'home_team' => new TeamResource(
                in_array('game.home_team', explode(',', $request->includes), true)
                    ? $this->homeTeam
                    : $this->whenLoaded('homeTeam')
            ),
            'away_team' => new TeamResource(
                in_array('game.away_team', explode(',', $request->includes), true)
                    ? $this->awayTeam
                    : $this->whenLoaded('awayTeam')
            ),
            'league' => new LeagueResource(
                in_array('game.league', explode(',', $request->includes), true)
                    ? $this->league
                    : $this->whenLoaded('league')
            ),
            'sport' => new SportResource(
                in_array('game.sport', explode(',', $request->includes), true)
                    ? $this->sport
                    : $this->whenLoaded('sport')
            ),
            'odds' => OddResource::collection(
                in_array('game.odds', explode(',', $request->includes), true)
                    ? $this->odds
                    : $this->whenLoaded('odds')
            ),
        ];
    }
}
