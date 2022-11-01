<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateAfterFilter
{
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->whereDate('date', '>=', $value);
    }
}
