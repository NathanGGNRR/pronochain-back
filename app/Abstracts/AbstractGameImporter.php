<?php

namespace App\Abstracts;

use App\Contracts\GameImporterInterface;
use App\Enums\Sports;
use App\Models\Game;
use App\Models\League;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class AbstractGameImporter implements GameImporterInterface
{
    private Sports $sport;

    final public function handle(Sports $sport): void
    {
        $this->sport = $sport;

        $games = $this->importGames($sport);
        $filtered_games = $this->filterGames($games);
        $parsed_games = $this->parseGames($filtered_games);
        $this->integrateGames($parsed_games);
    }

    abstract protected function importGames(Sports $sport): Collection;

    abstract protected function filterGames(Collection $data): Collection;

    abstract protected function parseGames($data): Collection;

    abstract protected function integrateGames(Collection $games): void;

    final protected function leagueSupported($league): bool
    {
        return in_array((string) $league->id, $this->getAvailableLeaguesCodes(), true);
    }

    final protected function gameIncoming(object $game): bool
    {
        $game_date = Str::before($game->date, 'T');

        return $game_date >= Carbon::today()->format('Y-m-d');
    }

    final protected function getAvailableLeaguesCodes(): array
    {
        return League::where('is_enabled', true)->pluck('code')->toArray();
    }

    final protected function findGame(object $game)
    {
        return $this->getQueryGame($game)->first();
    }

    final protected function gameModelExists(object $game): bool
    {
        return $this->getQueryGame($game)->exists();
    }

    private function getQueryGame($game): Builder
    {
        return Game::where([
            ['sport_id', '=', $this->sport->getModel()->id],
            ['code', '=', $game->id],
        ]);
    }
}
