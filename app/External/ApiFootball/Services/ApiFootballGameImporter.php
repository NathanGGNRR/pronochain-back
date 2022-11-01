<?php

namespace App\External\ApiFootball\Services;

use App\Abstracts\AbstractGameImporter;
use App\Enums\Sports;
use App\External\ApiFootball\Parsers\SoccerParser;
use App\Models\Country;
use App\Models\Game;
use App\Models\League;
use App\Models\Team;
use Illuminate\Support\Collection;

class ApiFootballGameImporter extends AbstractGameImporter
{
    private $service;

    protected function importGames(Sports $sport): Collection
    {
        if ($sport->value === Sports::SOCCER->value) {
            $this->service = new FootballService();
        }

        $current_page = 1;
        $data = collect();

        do {
            $odds = $this->service->getGamesWithAvailableOdds([
                'query' => [
                    'page' => $current_page,
                ],
            ]);

            $last_page = $odds->paging->total;
            $data = $data->merge(collect($odds->response));
            ++$current_page;
        } while ($current_page <= $last_page);

        return $data;
    }

    protected function filterGames(Collection $odds): Collection
    {
        return $odds->filter(function ($odd) {
            return ($this->leagueSupported($odd->league) && $this->gameIncoming($odd->fixture))
                || ($this->gameModelExists($odd->fixture) && $this->gameNotUpdated($this->findGame($odd->fixture), $odd));
        });
    }

    protected function parseGames($data): Collection
    {
        $parser = new SoccerParser();

        $games = collect();

        $data->each(function ($item) use ($parser, $games) {
            $api_game = $this->service->getGame($item->fixture->id);

            $api_game->fixture->odd_update = $item->update;

            $ht = $this->service->getTeam($api_game->teams->home->id)->team;
            $at = $this->service->getTeam($api_game->teams->away->id)->team;

            $ht_country = Country::whereCode($this->service->getCountries(['query' => [
                'name' => $ht->country,
            ]])[0]->code)->first();

            $at_country = Country::whereCode($this->service->getCountries(['query' => [
                'name' => $at->country,
            ]])[0]->code)->first();

            $home_team = $parser->parseTeam($ht)->addCountry($ht_country)->build();
            $away_team = $parser->parseTeam($at)->addCountry($at_country)->build();
            $league = $parser->parseLeague($api_game->league)->get();

            $game = $parser->parseGame($api_game->fixture);
            $game
                ->addHomeTeam($this->updateOrCreateTeam($home_team))
                ->addAwayTeam($this->updateOrCreateTeam($away_team))
                ->addLeague(League::whereCode($league->code)->firstOrFail())
            ;

            $games->push($game->build());
        });

        return $games;
    }

    protected function integrateGames(Collection $games): void
    {
        Game::upsert($games->toArray(), ['code', 'sport_id'], ['referee', 'date', 'league_id', 'home_team_id', 'away_team_id']);
    }

    private function updateOrCreateTeam($team): Team
    {
        return Team::updateOrCreate(
            ['code' => $team->code, 'sport_id' => $team->sport_id],
            ['name' => $team->name, 'logo' => $team->logo, 'country_id' => $team->country_id]
        );
    }

    private function gameNotUpdated($game, $odd): bool
    {
        return $game->updated_at->toAtomString() !== $odd->update;
    }
}
