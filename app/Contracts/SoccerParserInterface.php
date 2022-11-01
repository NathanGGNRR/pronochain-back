<?php

namespace App\Contracts;

interface SoccerParserInterface extends SportParserInterface
{
    public function parseGame(object $game);

    public function parseTeam(object $team);

    public function parseLeague(object $league);
}
