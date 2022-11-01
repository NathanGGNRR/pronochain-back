<?php

namespace App\Filters;

class GameFilter extends AbstractFilter
{
    protected array $filters = [
        'date' => DateFilter::class,
        'date_after' => DateAfterFilter::class,
        'with_available_odds' => WithAvailableOdds::class,
    ];
}
