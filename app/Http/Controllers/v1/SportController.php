<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SportCollection;
use App\Http\Resources\SportResource;
use App\Models\Sport;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): SportCollection
    {
        return new SportCollection(Sport::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Sport $sport): SportResource
    {
        return new SportResource($sport);
    }
}
