<?php

namespace App\External\ApiFootball\Services;

use App\Contracts\CountryApi;
use App\Contracts\SoccerApi;
use App\External\ApiFootball\Endpoints\FootballEndpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class FootballService implements SoccerApi, CountryApi, FootballEndpoints
{
    private const BASE_URL = 'https://api-football-v1.p.rapidapi.com/';
    private const VERSION = 'v3';

    private Client $client;

    public function __construct()
    {
        if (!isset($this->client)) {
            $this->client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-rapidapi-key' => env('API_FOOTBALL_KEY'),
                ],
            ]);
        }
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getCountries(array $options = []): array
    {
        $request = $this->client->get(self::VERSION.self::COUNTRIES, $options);

        return json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR)->response;
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getLeagues(array $options = []): array
    {
        $request = $this->client->get(self::VERSION.self::LEAGUES, $options);

        return json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR)->response;
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getGamesWithAvailableOdds(array $options = []): object
    {
        $request = $this->client->get(self::VERSION.self::AVAILABLE_ODDS, $options);

        return json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getGame(int $game_id, array $options = []): object
    {
        $request = $this->client->get(self::VERSION.self::GAMES, array_merge_recursive($options, ['query' => [
            'id' => $game_id,
            'timezone' => 'Europe/Paris',
        ]]));

        return json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR)->response[0];
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getGameOdds(int $game_id, array $options = []): object
    {
        $request = $this->client->get(self::VERSION.self::ODDS, array_merge_recursive($options, ['query' => [
            'fixture' => $game_id,
            'timezone' => 'Europe/Paris',
        ]]));

        return json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR)->response[0];
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getTeam(int $team_id, array $options = []): object
    {
        $request = $this->client->get(self::VERSION.self::TEAMS, array_merge_recursive($options, ['query' => [
            'id' => $team_id,
        ]]));

        return json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR)->response[0];
    }
}
