<?php

namespace App\Services;

use App\Models\Country;

class CountryManager
{
    /**
     * Countries names.
     */
    private const WORLD_COUNTRY_NAME = 'World';
    private const ENGLAND_COUNTRY_NAME = 'England';
    private const SCOTLAND_COUNTRY_NAME = 'Scotland';
    private const WALES_COUNTRY_NAME = 'Wales';
    private const NORTHERN_IRELAND_COUNTRY_NAME = 'Northern-Ireland';

    /**
     * Custom countries codes.
     */
    private const WORLD_COUNTRY_CODE = 'WORLD';
    private const ENGLAND_COUNTRY_CODE = 'EN';
    private const SCOTLAND_COUNTRY_CODE = 'SC';
    private const WALES_COUNTRY_CODE = 'WA';
    private const NORTHERN_IRELAND_COUNTRY_CODE = 'ND';

    /**
     * Try to find country model from country API object.
     */
    public function getCountryModelFromObject(object $country): mixed
    {
        return Country::where('code', $this->getCountryCode($country))->firstOrFail();
    }

    /**
     * Get country code from country API object.
     */
    public function getCountryCode(object $country): string
    {
        return match ($country->name) {
            self::ENGLAND_COUNTRY_NAME => self::ENGLAND_COUNTRY_CODE,
            self::WALES_COUNTRY_NAME => self::WALES_COUNTRY_CODE,
            self::SCOTLAND_COUNTRY_NAME => self::SCOTLAND_COUNTRY_CODE,
            self::NORTHERN_IRELAND_COUNTRY_NAME => self::NORTHERN_IRELAND_COUNTRY_CODE,
            self::WORLD_COUNTRY_NAME => self::WORLD_COUNTRY_CODE,
            default => $country->code,
        };
    }
}
