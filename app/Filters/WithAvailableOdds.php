<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class WithAvailableOdds
{
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->has('odds');
    }
}
