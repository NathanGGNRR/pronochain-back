<?php

namespace App\Builders;

use App\Models\Country;
use App\Models\Sport;
use App\Models\Team;

class TeamBuilder
{
    private Team $team;

    public function __construct()
    {
        $this->team = new Team();
    }

    public function setCode(string $code): self
    {
        $this->team->code = $code;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->team->name = $name;

        return $this;
    }

    public function addSport(Sport $sport): self
    {
        $this->team->sport_id = $sport->id;

        return $this;
    }

    public function setLogo(string $logo_url): self
    {
        $this->team->logo = $logo_url;

        return $this;
    }

    public function addCountry(Country $country): self
    {
        $this->team->country_id = $country->id;

        return $this;
    }

    public function build(): Team
    {
        return $this->team;
    }
}
