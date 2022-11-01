<?php

namespace App\Services;

use App\Contracts\OddsImporterInterface;
use App\Enums\OddTypes;
use App\Enums\Sports;
use App\Models\Game;
use App\Models\Odd;
use Carbon\Carbon;

class OddsImporter implements OddsImporterInterface
{
    public function handle(Sports $sport): void
    {
        $service = app()->make($sport->getInterface());

        $games = Game::doesntHave('odds')->whereDate('date', '>=', Carbon::now())->get();
        $games->each(function ($game) use ($service) {
            $odds = $service->getGameOdds($game->code);

            $key = $this->defineBookmakersKey($odds->bookmakers);
            $game->odds()->saveMany([
                new Odd(['odd_type_id' => OddTypes::HOME_WINNER->getModel()->id, 'value' => $this->getOddByKey($odds, 0, $key)]),
                new Odd(['odd_type_id' => OddTypes::AWAY_WINNER->getModel()->id, 'value' => $this->getOddByKey($odds, 2, $key)]),
                new Odd(['odd_type_id' => OddTypes::DRAW->getModel()->id, 'value' => $this->getOddByKey($odds, 1, $key)]),
            ]);
        });
    }

    private function getOddByKey($odds, $key, $bookmaker): float
    {
        return array_key_exists($key, $odds->bookmakers[$bookmaker]->bets[0]->values) ? $odds->bookmakers[$bookmaker]->bets[0]->values[$key]->odd : 1.00;
    }

    private function defineBookmakersKey($bookmakers): int
    {
        $key = array_search('Unibet', array_column($bookmakers, 'name'), true);

        if (false === $key) {
            $key = array_search('Bwin', array_column($bookmakers, 'name'), true);
        }

        return $key ?? 0;
    }
}
