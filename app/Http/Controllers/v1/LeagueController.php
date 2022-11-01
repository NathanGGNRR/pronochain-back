<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeagueCollection;
use App\Http\Resources\LeagueResource;
use App\Models\League;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): LeagueCollection
    {
        return new LeagueCollection(League::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(League $league): LeagueResource
    {
        return new LeagueResource($league);
    }
}
