<?php

namespace App\Factories;

use App\Builders\LeagueBuilder;
use App\Contracts\SportFactory;
use App\Enums\Sports;
use App\Models\League;
use App\Models\Sport;
use App\Services\CountryManager;

class SoccerFactory implements SportFactory
{
    private CountryManager $countryManager;
    private Sport $sport;

    public function __construct(CountryManager $countryManager)
    {
        $this->countryManager = $countryManager;
        $this->sport = Sports::SOCCER->getModel();
    }

    public function createLeague(object $league): League
    {
        return (new LeagueBuilder())
            ->setCode($league->league->id)
            ->setName($league->league->name)
            ->setType($league->league->type)
            ->addSport($this->sport)
            ->addCountry($this->countryManager->getCountryModelFromObject($league->country))
            ->save()
            ->get()
        ;
    }
}
