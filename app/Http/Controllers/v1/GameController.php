<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): GameCollection
    {
        return new GameCollection(Game::filter($request)->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game): GameResource
    {
        return new GameResource($game);
    }
}
