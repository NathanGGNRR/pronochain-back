<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateFilter
{
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->whereDate('date', $value);
    }
}
