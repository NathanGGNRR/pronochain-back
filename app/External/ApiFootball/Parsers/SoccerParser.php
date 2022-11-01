<?php

namespace App\External\ApiFootball\Parsers;

use App\Builders\GameBuilder;
use App\Builders\LeagueBuilder;
use App\Builders\TeamBuilder;
use App\Contracts\SoccerParserInterface;
use App\Enums\Sports;
use App\Models\Sport;

class SoccerParser implements SoccerParserInterface
{
    private Sport $sport;

    public function __construct()
    {
        $this->sport = Sports::SOCCER->getModel();
    }

    public function parseGame(object $game): GameBuilder
    {
        return (new GameBuilder())
            ->setCode($game->id)
            ->setDate($game->date)
            ->setReferee($game->referee)
            ->setUpdatedAt($game->odd_update)
            ->addSport($this->sport)
        ;
    }

    public function parseTeam(object $team): TeamBuilder
    {
        return (new TeamBuilder())
            ->setCode($team->id)
            ->setName($team->name)
            ->setLogo($team->logo)
            ->addSport($this->sport)
        ;
    }

    public function parseLeague(object $league): LeagueBuilder
    {
        return (new LeagueBuilder())
            ->setCode($league->id)
            ->setName($league->name)
            ->addSport($this->sport)
        ;
    }
}
