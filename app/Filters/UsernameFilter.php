<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class UsernameFilter
{
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->where('username', 'like', '%'.$value.'%');
    }
}
