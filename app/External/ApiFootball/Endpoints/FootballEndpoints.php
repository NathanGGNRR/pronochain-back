<?php

namespace App\External\ApiFootball\Endpoints;

interface FootballEndpoints
{
    public const COUNTRIES = '/countries';
    public const LEAGUES = '/leagues';
    public const AVAILABLE_ODDS = '/odds/mapping';
    public const GAMES = '/fixtures';
    public const ODDS = '/odds';
    public const TEAMS = '/teams';
}
