<?php

namespace App\Contracts;

interface SoccerApi
{
    public function getLeagues(array $options = []): array;

    public function getGamesWithAvailableOdds(array $options = []): object;

    public function getGame(int $game_id, array $options = []): object;

    public function getGameOdds(int $game_id, array $options = []): object;
}
