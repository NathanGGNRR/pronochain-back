<?php

namespace App\Builders;

use App\Models\Country;
use App\Models\League;
use App\Models\Sport;

class LeagueBuilder
{
    private League $league;

    public function __construct()
    {
        $this->league = new League();
    }

    public function setCode(string $code): self
    {
        $this->league->code = $code;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->league->name = $name;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->league->type = $type;

        return $this;
    }

    public function addSport(Sport $sport): self
    {
        $this->league->sport_id = $sport->id;

        return $this;
    }

    public function addCountry(Country $country): self
    {
        $this->league->country_id = $country->id;

        return $this;
    }

    public function save(): self
    {
        $this->league->save();

        return $this;
    }

    public function get(): League
    {
        return $this->league;
    }
}
