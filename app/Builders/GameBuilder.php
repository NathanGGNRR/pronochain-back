<?php

namespace App\Builders;

use App\Models\Game;
use App\Models\League;
use App\Models\Sport;
use App\Models\Team;

class GameBuilder
{
    private Game $game;

    public function __construct()
    {
        $this->game = new Game();
    }

    public function setCode(string $code): self
    {
        $this->game->code = $code;

        return $this;
    }

    public function setDate(string $date): self
    {
        $this->game->date = $date;

        return $this;
    }

    public function setReferee(?string $referee): self
    {
        $this->game->referee = $referee;

        return $this;
    }

    public function setUpdatedAt(string $updated_at): self
    {
        $this->game->updated_at = $updated_at;

        return $this;
    }

    public function addHomeTeam(Team $team): self
    {
        $this->game->home_team_id = $team->id;

        return $this;
    }

    public function addAwayTeam(Team $team): self
    {
        $this->game->away_team_id = $team->id;

        return $this;
    }

    public function addSport(Sport $sport): self
    {
        $this->game->sport_id = $sport->id;

        return $this;
    }

    public function addLeague(League $league): self
    {
        $this->game->league_id = $league->id;

        return $this;
    }

    public function build(): Game
    {
        return $this->game;
    }
}
