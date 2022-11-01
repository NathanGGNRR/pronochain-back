<?php

namespace App\Contracts;

use App\Models\League;

interface SportFactory
{
    public function createLeague(object $league): League;
}
