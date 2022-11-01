<?php

namespace App\Contracts;

interface CountryApi
{
    public function getCountries(array $options = []): array;
}
