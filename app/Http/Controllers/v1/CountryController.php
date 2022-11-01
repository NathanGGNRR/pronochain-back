<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryCollection;
use App\Http\Resources\CountryResource;
use App\Models\Country;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): CountryCollection
    {
        return new CountryCollection(Country::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country): CountryResource
    {
        return new CountryResource($country);
    }
}
